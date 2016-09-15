<?php

use yii\db\Schema;
use yii\db\Migration;

class m160913_051001_create_imdb extends Migration
{
    public function up()
    {
        $this->createTable('{{%imdb}}', [
            'id' => Schema::TYPE_STRING . ' NOT NULL PRIMARY KEY',
            'rating' => $this->double(),
            'max_rating' => $this->integer(),
            'num_review' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%imdb}}');
    }
}
