<?php
namespace common\components;

use Yii;
use Tmdb\ApiToken;
use Tmdb\Client;
use Tmdb\Repository\ConfigurationRepository;
use Tmdb\Repository\MovieRepository;
use common\models\Movie;
use yii\web\HttpException;

class TmdbHelper
{
    // Mine'd8d63599c8150e8613bfe18d54722457';
    // search : '3b03c053f34ff11cfdc0d26b06ac95d1'  
    // '3d197569c7f13f60d61a7d61d5c83427'
    // f22e6ce68f5e5002e71c20bcba477e7d
    // 470fd2ec8853e25d2f8d86f685d2270e
    CONST API_KEY = 'd8d63599c8150e8613bfe18d54722457'; //main


    public static function createNewMovie($id)
    {
        $token  = new ApiToken(self::API_KEY);
        $client = new Client($token, [
            'secure' => false,
        ]);

        $configRepository = new ConfigurationRepository($client);
        $config = $configRepository->load();

        $repository  = new MovieRepository($client);

        $newMovie = $repository->load($id);

        $movie = new Movie();
        $movie->tmdb_id = $newMovie->getId();
        $movie->imdb_id = $newMovie->getImdbId(); 

        $movie->title = $newMovie->getTitle();
        $movie->overview = $newMovie->getOverview();
        $movie->homepage = $newMovie->getHomepage();
        $movie->poster = $newMovie->getPosterPath();
        $movie->runtime = $newMovie->getRuntime();

        foreach ($newMovie->getReleases() as $release) {
            if(in_array($release->getIso31661(), ['US', 'TH'])){
                $movie->rate = $release->getCertification();
                $movie->release = $release->getReleaseDate()->getTimestamp();
                break;
            }
        }

        $arrayTemp = [];
        foreach($newMovie->getGenres() as $genre){
            $arrayTemp[$genre->getId()] = $genre->getName();
        }
        $movie->genre = $arrayTemp;

        $arrayTemp = [];
        foreach($newMovie->getImages() as $image) {
            $arrayTemp[] = $image->getFilePath();
        }
        $movie->image = $arrayTemp;

        $arrayTemp = [];
        foreach ($newMovie->getVideos() as $trailer) {
            $arrayTemp[] = $trailer->getUrl();
        }
        $movie->trailer = $arrayTemp;

        $arrayTemp = [];
        foreach($newMovie->getCredits()->getCast() as $key => $person){
            $arrayTemp[$key]['tmdb_id'] = $person->getId();
            $arrayTemp[$key]['full_name'] = $person->getName();
            $arrayTemp[$key]['image_path'] = $person->getProfilePath();
            $arrayTemp[$key]['charecter'] = $person->getCharacter();
            $arrayTemp[$key]['job'] = '';
            $arrayTemp[$key]['type'] = 1;
        }
        foreach($newMovie->getCredits()->getCrew() as $key => $person){
            $arrayTemp[$key]['tmdb_id'] = $person->getId();
            $arrayTemp[$key]['full_name'] = $person->getName();
            $arrayTemp[$key]['image_path'] = $person->getProfilePath();
            $arrayTemp[$key]['charecter'] = '';
            $arrayTemp[$key]['job'] = $person->getJob();
            $arrayTemp[$key]['type'] = 2;
        }
        $movie->people = $arrayTemp;

        if($movie->save()){
            return $movie;
        }

        throw new HttpException(500, 'Something went wrong!!');
    }    
}