<?php

namespace common\models;

use Yii;
use common\components\MActiveRecord;

/**
 * This is the model class for table "people".
 *
 * @property integer $id
 * @property integer $tmdb_id
 * @property string $full_name
 * @property string $image_path
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class People extends MActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'people';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tmdb_id'], 'required'],
            [['tmdb_id'], 'unique'],
            [['type', 'created_at', 'updated_at', 'tmdb_id'], 'integer'],
            [['full_name', 'image_path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tmdb_id' => 'Tmdb_id',
            'full_name' => 'Full Name',
            'image_path' => 'Image Path',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getMoviePeople()
    {
        return $this->hasMany(MoviePeople::className(), [
            'people_id' => 'id'
        ]);
    }

    public function getMovie()
    {
        return $this->hasMany(Movie::className(), [
            'id' => 'movie_id'
        ])->via('moviePeople');
    }
}
