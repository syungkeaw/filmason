<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Movie;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\components\TmdbHelper;
use Tmdb\ApiToken;
use Tmdb\Client;
use Tmdb\Repository\ConfigurationRepository;
use Tmdb\Repository\MovieRepository;
use common\models\Filmason;

/**
 * Movie controller
 */
class MovieController extends Controller
{

    public function actionIndex($id, $name)
    {

        $movie = Movie::findOne(['tmdb_id' => $id]);

        if(!isset($movie) || empty($movie)){
            echo 'create new<br>';
            $movie = TmdbHelper::createNewMovie($id);
        }

        if(empty($movie->imdb->id)){
            $imdbService = new \common\components\Imdb();
            $imdb = new \common\models\Imdb();
            $mArr = $imdbService->getMovieInfoById($movie->imdb_id);
            $imdb->id = $movie->imdb_id;
            $imdb->rating = $mArr['rating_value'];
            $imdb->max_rating = 10;
            $imdb->num_review = intval(str_replace(',', '', $mArr['rating_count']));
            $imdb->save();
            $movie = Movie::findOne(['tmdb_id' => $id]);
        }else if((time() - $movie->imdb->updated_at) > (3600*8)){
            $imdbService = new \common\components\Imdb();
            $mArr = $imdbService->getMovieInfoById($movie->imdb_id);
            $imdb = \common\models\Imdb::findOne($movie->imdb_id);
            $imdb->rating = $mArr['rating_value'];
            $imdb->num_review = intval(str_replace(',', '', $mArr['rating_count']));
            $imdb->save();
            $movie = Movie::findOne(['tmdb_id' => $id]);
        }

        if(empty($movie->rt->id)){
            $rtService = new \common\components\Rt();
            $mArr = $rtService->getMovieInfo($movie->title);

            $rt = new \common\models\Rt();
            $rt->id = $movie->imdb_id;

            $rt->url = $mArr['url'];
            $rt->critic_meter_tomato = $mArr['critic_meter_tomato'];
            $rt->critic_score = $mArr['critic_score'];
            $rt->average_rating = $mArr['average_rating'];
            $rt->reviews_counted = $mArr['reviews_counted'];
            $rt->fresh = $mArr['fresh'];
            $rt->rotten = $mArr['rotten'];
            $rt->user_meter_tomato = $mArr['user_meter_tomato'];
            $rt->user_score = $mArr['user_score'];
            $rt->user_average_rating = $mArr['user_average_rating'];
            $rt->user_reviews_counted = intval(str_replace(',', '', $mArr['user_reviews_counted']));

            $rt->save();
            $movie = Movie::findOne(['tmdb_id' => $id]);
        }else if((time() - $movie->rt->updated_at) > (3600*12)){
            $rtService = new \common\components\Rt();
            $mArr = $rtService->getMovieInfo($movie->title);
            $rt = \common\models\Rt::findOne($movie->imdb_id);

            $rt->critic_meter_tomato = $mArr['critic_meter_tomato'];
            $rt->critic_score = $mArr['critic_score'];
            $rt->average_rating = $mArr['average_rating'];
            $rt->reviews_counted = $mArr['reviews_counted'];
            $rt->fresh = $mArr['fresh'];
            $rt->rotten = $mArr['rotten'];
            $rt->user_meter_tomato = $mArr['user_meter_tomato'];
            $rt->user_score = $mArr['user_score'];
            $rt->user_average_rating = $mArr['user_average_rating'];
            $rt->user_reviews_counted = intval(str_replace(',', '', $mArr['user_reviews_counted']));

            $rt->save();
            $movie = Movie::findOne(['tmdb_id' => $id]);
        }

        if(Yii::$app->request->get('vote') === 'g' || Yii::$app->request->get('vote') === 'b'){
            $this->vote($movie);
        }
        // echo '<pre>', print_r($rt->getErrors());
        // die;

        return $this->render('index', [
            'movie' => $movie,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actionSearch($query)
    {
        // $this->layout = "layoutName";
        // $this->layout('folder/namefile')
        return $this->render('search', ['query'=>$query]);
    }

    private function vote($movie)
    {
        $vote = Yii::$app->request->get('vote');
        $filmason = Filmason::findOne(['imdb_id' => $movie->imdb_id, 'voted_by' => Yii::$app->user->identity->id]);
        if(empty($filmason)){
            $filmason = new Filmason();
            $filmason->imdb_id = $movie->imdb_id;
            $filmason->voted_by = Yii::$app->user->identity->id;
        }else if ($filmason->vote == $vote){
            $vote = 'd';
        }

        $filmason->updated_at = time();
        $filmason->vote = $vote;

        $filmason->save();
    }
}
