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
class Sf
{	
	public function getNowShowingMovies()
	{
		$url = 'http://www.sfcinemacity.com/index.php/th/movies/nowshowing';
		return $this->scrapeMovieInfoNew($url);
	}
	
	private function scrapeMovieInfoNew($url){
		$arr = [];
		$html = $this->geturl($url);

		$arr['showing'] = $this->match_all('/<h3>[\s]*<a href="http:\/\/www\.sfcinemacity\.com\/index\.php\/th\/movie-detail\/([\w-]+)"/ms', $html, 1);

		// echo '<pre>', print_r($arr); die;
		return $arr;
	}

	private function geturl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
		$html = curl_exec($ch);
		if(curl_error($ch))
		{
		    echo 'error:' . curl_error($ch);
		}
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
