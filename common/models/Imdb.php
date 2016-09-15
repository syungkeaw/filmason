<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "imdb".
 *
 * @property integer $id
 * @property integer $rating
 * @property integer $max_rating
 * @property integer $num_review
 * @property integer $created_at
 * @property integer $updated_at
 */
class Imdb extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }   

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imdb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['max_rating', 'num_review', 'created_at', 'updated_at'], 'integer'],
            [['rating', ], 'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rating' => Yii::t('app', 'Rating'),
            'max_rating' => Yii::t('app', 'Max Rating'),
            'num_review' => Yii::t('app', 'Num Review'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
