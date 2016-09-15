<?php

namespace common\models;

use Yii;
use common\components\MActiveRecord;

/**
 * This is the model class for table "movie".
 *
 * @property integer $id
 * @property integer $tmdb_id
 * @property string $imdb_id
 * @property string $title
 * @property string $title_2
 * @property string $overview
 * @property string $overview_2
 * @property string $homepage
 * @property string $poster
 * @property integer $release
 * @property string $rate
 * @property string $runtime
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Movie extends MActiveRecord
{

    private $_genre;
    private $_image;
    private $_trailer;
    private $_people;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movie';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmdb_id', 'imdb_id', 'title',], 'required'],
            [['tmdb_id', 'release', 'status', 'runtime', 'created_at', 'updated_at'], 'integer'],
            [['overview', 'overview_2'], 'string'],
            [['imdb_id', 'title', 'title_2', 'homepage', 'poster', 'rate',], 'string', 'max' => 255],
            [['tmdb_id'], 'unique'],
            [['imdb_id'], 'unique'],
            [['genre', 'image', 'trailer', 'people'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tmdb_id' => 'Tmdb ID',
            'imdb_id' => 'Imdb ID',
            'title' => 'Title',
            'title_2' => 'Title 2',
            'overview' => 'Overview',
            'overview_2' => 'Overview 2',
            'homepage' => 'Homepage',
            'poster' => 'Poster',
            'release' => 'Release',
            'rate' => 'Rate',
            'runtime' => 'Runtime',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @Genre handle
     */
    public function getGenre()
    {
        return $this->hasMany(Genre::className(), [
            'movie_id' => 'id'
        ]);
    }

    public function setGenre($value)
    {
        $this->_genre = $value;
    }

    public function saveGenre()
    {
        $this->unlinkAll('genre', true);
        if (isset($this->_genre) && is_array($this->_genre)) {
            foreach ($this->_genre as $genreLabel) {
                $genre = new Genre();
                $genre->label = $genreLabel;
                $genre->link('movie', $this);
            }
        }
    }

    /**
     * @Image handle
     */
    public function getImage()
    {
        return $this->hasMany(Image::className(), [
            'movie_id' => 'id'
        ]);
    }

    public function setImage($value)
    {
        $this->_image = $value;
    }

    public function saveImage()
    {
        $this->unlinkAll('image', true);
        if (isset($this->_image) && is_array($this->_image)) {
            foreach ($this->_image as $path) {
                $image = new Image();
                $image->path = $path;
                $image->link('movie', $this);
            }
        }
    }

    /**
     * @Trailer handle
     */
    public function getTrailer()
    {
        return $this->hasMany(Trailer::className(), [
            'movie_id' => 'id'
        ]);
    }

    public function setTrailer($value)
    {
        $this->_trailer = $value;
    }

    public function saveTrailer()
    {
        $this->unlinkAll('trailer', true);
        if (isset($this->_trailer) && is_array($this->_trailer)) {
            foreach ($this->_trailer as $path) {
                $trailer = new Trailer();
                $trailer->path = $path;
                $trailer->link('movie', $this);
            }
        }
    }

    /**
     * @People handle
     */
    public function getMoviePeople()
    {
        return $this->hasMany(MoviePeople::className(), [
            'movie_id' => 'id'
        ]);
    }

    public function saveMoviePeople()
    {
        $this->unlinkAll('moviePeople', true);
        if (isset($this->_people) && is_array($this->_people)) {
            foreach ($this->_people as $newPeople) {
                $people = People::findOne(['tmdb_id' => $newPeople['tmdb_id'],]);
                if(isset($people) && !empty($people)){
                    $people->link('movie', $this, [
                        'job' => $newPeople['job'],
                        'charecter' => $newPeople['charecter'],
                    ]);
                }
            }
        }
    }

    ################
    public function getPeople()
    {
        return $this->hasMany(People::className(), [
            'id' => 'people_id'
        ])->via('moviePeople');
    }  

    public function setPeople($value)
    {
        $this->_people = $value;
    }

    public function savePeople()
    {
        $returnFlag = true;

        if (isset($this->_people) && is_array($this->_people)) {
            foreach ($this->_people as $newPeople) {
                $people = People::findOne(['tmdb_id' => $newPeople['tmdb_id'],]);

                if(!isset($people) || empty($people))
                    $people = new People();

                $people->tmdb_id = $newPeople['tmdb_id'];
                $people->full_name = $newPeople['full_name'];
                $people->image_path = $newPeople['image_path'];
                $people->type = $newPeople['type'];
                $returnFlag &= $people->save();
            }
        }

        return $returnFlag;
    }

    /**
     * @All handle
     */

    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        
        return $this->savePeople();
    }    

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->saveGenre();
        $this->saveImage();
        $this->saveTrailer();
        $this->saveMoviePeople();
    }    

    public function getImdb()
    {
        return $this->hasOne(Imdb::className(), [
            'id' => 'imdb_id'
        ]);
    }  

    public function getRt()
    {
        return $this->hasOne(Rt::className(), [
            'id' => 'imdb_id'
        ]);
    }  

    public function getBadCount()
    {
        return $this->hasMany(Filmason::className(), [
            'imdb_id' => 'imdb_id',
        ])->andFilterWhere(['vote' => 'b',])->count();
    }  

    public function getGoodCount()
    {
        return $this->hasMany(Filmason::className(), [
            'imdb_id' => 'imdb_id',
        ])->andFilterWhere(['vote' => 'g',])->count();
    }  

    public function getMyVote()
    {
        if(Yii::$app->user->isGuest){
            return 0;
        }

        return $this->hasOne(Filmason::className(), [
            'imdb_id' => 'imdb_id',
        ])->andFilterWhere(['voted_by' => Yii::$app->user->identity->id,]);
    }  
}
