<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_092453_create_movie_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%movie}}', [
            'id' => $this->primaryKey(),
            'tmdb_id' => $this->integer()->notNull()->unique(),
            'imdb_id' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'title_2' => $this->string(),
            'overview' => $this->text(),
            'overview_2' => $this->text(),
            'homepage' => $this->string(),
            'poster' => $this->string(),
            'release' => $this->integer(),
            'rate' => $this->string(),
            'runtime' => $this->integer(),
            'poster' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%movie}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
