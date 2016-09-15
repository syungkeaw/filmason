<?php

use yii\db\Schema;
use yii\db\Migration;

class m160913_111700_create_rt extends Migration
{
    public function up()
    {
        $this->createTable('{{%rt}}', [
            'id' => Schema::TYPE_STRING . ' NOT NULL PRIMARY KEY',
            'url' => $this->string(),
            'critic_meter_tomato' => $this->string(),
            'critic_score' => $this->integer(),
            'average_rating' => $this->double(),
            'reviews_counted' => $this->integer(),
            'fresh' => $this->integer(),
            'rotten' => $this->integer(),
            'user_meter_tomato' => $this->string(),
            'user_score' => $this->integer(),
            'user_average_rating' => $this->double(),
            'user_reviews_counted' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%rt}}');
    }
}
