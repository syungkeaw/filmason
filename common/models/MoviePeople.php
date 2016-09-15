<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "movie_people".
 *
 * @property integer $id
 * @property integer $movie_id
 * @property integer $people_id
 * @property string $job
 * @property string $charecter
 */
class MoviePeople extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movie_people';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movie_id', 'people_id'], 'required'],
            [['movie_id', 'people_id'], 'integer'],
            [['job', 'charecter'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'movie_id' => 'Movie ID',
            'people_id' => 'People ID',
            'job' => 'Job',
            'charecter' => 'Charecter',
        ];
    }

    public function getMovie()
    {
        return $this->hasOne(Movie::className(), [
            'id' => 'movie_id'
        ]);
    }  

    public function getPeople()
    {
        return $this->hasOne(People::className(), [
            'id' => 'people_id'
        ]);
    }  
}
