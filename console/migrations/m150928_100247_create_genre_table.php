<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_100247_create_genre_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%genre}}', [
            'id' => $this->primaryKey(),
            'movie_id' => $this->integer()->notNull(),
            'label' => $this->string()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%genre}}');
    }
}
