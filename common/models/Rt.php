<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rt".
 *
 * @property string $id
 * @property string $url
 * @property string $critic_meter_tomato
 * @property integer $critic_score
 * @property integer $average_rating
 * @property integer $reviews_counted
 * @property integer $fresh
 * @property integer $rotten
 * @property string $user_meter_tomato
 * @property integer $user_score
 * @property integer $user_average_rating
 * @property integer $user_reviews_counted
 * @property integer $created_at
 * @property integer $updated_at
 */
class Rt extends \yii\db\ActiveRecord
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
        return 'rt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            // [['critic_score', 'reviews_counted', 'fresh', 'rotten', 'user_score', 'user_reviews_counted', 'created_at', 'updated_at'], 'integer'],
            [['id', 'url', 'critic_meter_tomato', 'user_meter_tomato'], 'string', 'max' => 255],
            // [['average_rating', 'user_average_rating', ], 'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'critic_meter_tomato' => Yii::t('app', 'Critic Meter Tomato'),
            'critic_score' => Yii::t('app', 'Critic Score'),
            'average_rating' => Yii::t('app', 'Average Rating'),
            'reviews_counted' => Yii::t('app', 'Reviews Counted'),
            'fresh' => Yii::t('app', 'Fresh'),
            'rotten' => Yii::t('app', 'Rotten'),
            'user_meter_tomato' => Yii::t('app', 'User Meter Tomato'),
            'user_score' => Yii::t('app', 'User Score'),
            'user_average_rating' => Yii::t('app', 'User Average Rating'),
            'user_reviews_counted' => Yii::t('app', 'User Reviews Counted'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
