<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "now_showing".
 *
 * @property integer $id
 * @property string $imdb_id
 * @property string $sf_title
 * @property integer $created_at
 * @property integer $updated_at
 */
class NowShowing extends \yii\db\ActiveRecord
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
        return 'now_showing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['imdb_id', 'sf_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'imdb_id' => Yii::t('app', 'Imdb ID'),
            'sf_title' => Yii::t('app', 'Sf Title'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $movie = TmdbHelper::createNewMovie($id);
        }
    }   
}
