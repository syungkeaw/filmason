<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_101428_create_popular_movie_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%popular_movie}}', [
            'id' => $this->primaryKey(),
            'movie_id' => $this->integer()->notNull()->unique(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%popular_movie}}');
    }
}
