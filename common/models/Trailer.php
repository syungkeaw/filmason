<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "trailer".
 *
 * @property integer $id
 * @property integer $movie_id
 * @property string $path
 */
class Trailer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trailer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movie_id', 'path'], 'required'],
            [['movie_id'], 'integer'],
            [['path'], 'string', 'max' => 255]
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
            'path' => 'Path',
        ];
    }

    public function getMovie()
    {
        return $this->hasOne(Movie::className(), [
            'id' => 'movie_id'
        ]);
    }

}
