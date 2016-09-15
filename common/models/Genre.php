<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "genre".
 *
 * @property integer $id
 * @property integer $movie_id
 * @property string $label
 */
class Genre extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'genre';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movie_id', 'label'], 'required'],
            [['movie_id'], 'integer'],
            [['label'], 'string', 'max' => 255]
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
            'label' => 'Label',
        ];
    }

    public function getMovie()
    {
        return $this->hasOne(Movie::className(), [
            'id' => 'movie_id'
        ]);
    }
}
