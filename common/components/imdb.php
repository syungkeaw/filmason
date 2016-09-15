<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////
// Free PHP IMDb Scraper API for the new IMDb Template.
// Version: 4.4
// Author: Abhinay Rathore
// Website: http://www.AbhinayRathore.com
// Blog: http://web3o.blogspot.com
// Demo: http://lab.abhinayrathore.com/imdb/
// More Info: http://web3o.blogspot.com/2010/10/php-imdb-scraper-for-new-imdb-template.html
// Last Updated: May 6, 2014
/////////////////////////////////////////////////////////////////////////////////////////////////////////
namespace common\components;

ini_set('max_execution_time', 5*60);
class Imdb
{	
	// Get movie information by Movie Title.
	// This method searches the given title on Google, Bing or Ask to get the best possible match.
	public function getMovieInfo($title, $getExtraInfo = true)
	{
		$imdbId = 'tt4160708';//$this->getIMDbIdFromSearch(trim($title));
		if($imdbId === NULL){
			$arr = array();
			$arr['response'] = "No Title found in Search Results!";
			return $arr;
		}
		return $this->getMovieInfoById($imdbId, $getExtraInfo);
	}
	
	// Get movie information by IMDb Id.
	public function getMovieInfoById($imdbId, $getExtraInfo = true)
	{
		$imdbUrl = "http://www.imdb.com/title/" . trim($imdbId) . "/";
		return $this->scrapeMovieInfoNew($imdbUrl, $getExtraInfo);
	}

	private function scrapeMovieInfoNew($imdbUrl, $getExtraInfo = true){
		$arr = [];
		$html = $this->geturl("${imdbUrl}");
		$arr['rating_value'] = $this->match('/itemprop="ratingValue">([0-9.]*)</ms', $html, 1);
		$arr['rating_count'] = $this->match('/itemprop="ratingCount">([0-9,]*)</ms', $html, 1);
		$arr['response'] = 'ok';
		return $arr;
	}
	
	// Scrape movie information from IMDb page and return results in an array.
	private function scrapeMovieInfo($imdbUrl, $getExtraInfo = true)
	{
		$arr = array();
		$html = $this->geturl("${imdbUrl}combined");
		$title_id = $this->match('/<link rel="canonical" href="http:\/\/www.imdb.com\/title\/(tt\d+)\/combined" \/>/ms', $html, 1);
		if(empty($title_id) || !preg_match("/tt\d+/i", $title_id)) {
			$arr['response'] = "No Title found on IMDb!";
			return $arr;
		}
		$arr['title_id'] = $title_id;
		$arr['title'] = str_replace('"', '', trim($this->match('/<title>(IMDb \- )*(.*?) \(.*?<\/title>/ms', $html, 2)));
		$arr['original_title'] = trim($this->match('/class="title-extra">(.*?)</ms', $html, 1));
		$arr['year'] = trim($this->match('/<title>.*?\(.*?(\d{4}).*?\).*?<\/title>/ms', $html, 1));
		$arr['rating'] = $this->match('/<b>(\d.\d)\/10<\/b>/ms', $html, 1);
		$arr['genres'] = $this->match_all('/<a.*?>(.*?)<\/a>/ms', $this->match('/Genre.?:(.*?)(<\/div>|See more)/ms', $html, 1), 1);
		$arr['directors'] = $this->match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', $this->match('/Directed by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
		$arr['producers'] = $this->match_all_key_value('/<td valign="top"><a.*?href="\/name\/(.*?)\/">(.*?)<\/a>/ms', $this->match('/Produced by<\/a><\/h5>(.*?)<\/table>/ms', $html, 1));
		$arr['cast'] = $this->match_all_key_value('/<td class="nm"><a.*?href="\/name\/(.*?)\/".*?>(.*?)<\/a>/ms', $this->match('/<h3>Cast<\/h3>(.*?)<\/table>/ms', $html, 1));
		$arr['stars'] = array_slice($arr['cast'], 0, 5);
		$arr['mpaa_rating'] = $this->match('/MPAA<\/a>:<\/h5><div class="info-content">Rated (G|PG|PG-13|PG-14|R|NC-17|X) /ms', $html, 1);
		$arr['release_date'] = $this->match('/Release Date:<\/h5>.*?<div class="info-content">.*?([0-9][0-9]? (January|February|March|April|May|June|July|August|September|October|November|December) (19|20)[0-9][0-9])/ms', $html, 1);
		$arr['poster'] = $this->match('/<div class="photo">.*?<a name="poster".*?><img.*?src="(.*?)".*?<\/div>/ms', $html, 1);
		$arr['poster_large'] = "";
		$arr['poster_full'] = "";
		if ($arr['poster'] != '' && strpos($arr['poster'], "media-imdb.com") > 0) { //Get large and small posters
			$arr['poster'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY200.jpg", $arr['poster']);
			// $arr['poster_large'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY500.jpg", $arr['poster']);
			// $arr['poster_full'] = preg_replace('/_V1.*?.jpg/ms', "_V1._SY0.jpg", $arr['poster']);
		} else {
			$arr['poster'] = "";
		}
		$arr['runtime'] = trim($this->match('/Runtime:<\/h5><div class="info-content">.*?(\d+) min.*?<\/div>/ms', $html, 1));
		$arr['top_250'] = trim($this->match('/Top 250: #(\d+)</ms', $html, 1));
		$arr['oscars'] = trim($this->match('/Won (\d+) Oscars?\./ms', $html, 1));
		if(empty($arr['oscars']) && preg_match("/Won Oscar\./i", $html)) $arr['oscars'] = "1";
		$arr['awards'] = trim($this->match('/(\d+) wins/ms',$html, 1));
		$arr['nominations'] = trim($this->match('/(\d+) nominations/ms',$html, 1));
		$arr['votes'] = $this->match('/>([0-9,]*) votes</ms', $html, 1);
		$arr['rating_count'] = $this->match('/itemprop="ratingCount">([0-9,]*)</ms', $html, 1);
		$arr['recommended_titles'] = $this->getRecommendedTitles($arr['title_id']);

		$arr['response'] = 'ok';
		return $arr;
	}
	
	// Get recommended titles by IMDb title id.
	public function getRecommendedTitles($titleId){
		$json = $this->geturl("http://www.imdb.com/widget/recommendations/_ajax/get_more_recs?specs=p13nsims%3A${titleId}");
		$resp = json_decode($json, true);
		$arr = array();
		if(isset($resp["recommendations"])) {
			foreach($resp["recommendations"] as $val) {
				$name = $this->match('/title="(.*?)"/msi', $val['content'], 1);
				$arr[$val['tconst']] = $name;
			}
		}
		return array_filter($arr);
	}
	

	//************************[ Extra Functions ]******************************

	// Movie title search on Google, Bing or Ask. If search fails, return FALSE.
	private function getIMDbIdFromSearch($title, $engine = "google"){
		switch ($engine) {
			case "google":  $nextEngine = "bing";  break;
			case "bing":    $nextEngine = "ask";   break;
			case "ask":     $nextEngine = FALSE;   break;
			case FALSE:     return NULL;
			default:        return NULL;
		}
		$url = "http://www.${engine}.com/search?q=imdb+" . rawurlencode($title);
		$ids = $this->match_all('/<a.*?href="http:\/\/www.imdb.com\/title\/(tt\d+).*?".*?>.*?<\/a>/ms', $this->geturl($url), 1);
		if (!isset($ids[0]) || empty($ids[0])) //if search failed
			return $this->getIMDbIdFromSearch($title, $nextEngine); //move to next search engine
		else
			return $ids[0]; //return first IMDb result
	}

	public function listIMDbIdFromSearch($title){
		$data = $this->geturl('http://www.imdb.com/xml/find?json=1&nr=1&tt=on&q='.$title);
		return json_decode($data);
	}	

	private function geturl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	private function match_all_key_value($regex, $str, $keyIndex = 1, $valueIndex = 2){
		$arr = array();
		preg_match_all($regex, $str, $matches, PREG_SET_ORDER);
		foreach($matches as $m){
			$arr[$m[$keyIndex]] = $m[$valueIndex];
		}
		return $arr;
	}
	
	private function match_all($regex, $str, $i = 0){
		if(preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches[$i];
	}

	private function match_all_arr($regex, $str){
		if(preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches;
	}


	private function match($regex, $str, $i = 0){
		if(preg_match($regex, $str, $match) == 1)
			return $match[$i];
		else
			return false;
	}/////////////////////////////////////////////////////////////////////////////////////////////////////////

}
?>
