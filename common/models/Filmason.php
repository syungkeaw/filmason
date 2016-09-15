<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "filmason".
 *
 * @property integer $id
 * @property string $imdb_id
 * @property integer $vote
 * @property integer $voted_by
 * @property integer $updated_at
 */
class Filmason extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filmason';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voted_by', 'updated_at'], 'integer'],
            [['vote', 'imdb_id'], 'string', 'max' => 255]
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
            'vote' => Yii::t('app', 'Vote'),
            'voted_by' => Yii::t('app', 'Voted By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
