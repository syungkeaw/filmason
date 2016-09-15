<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_101328_create_movie_people_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%movie_people}}', [
            'id' => $this->primaryKey(),
            'movie_id' => $this->integer()->notNull(),
            'people_id' => $this->integer()->notNull(),
            'job' => $this->string(),
            'charecter' => $this->string(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%movie_people}}');
    }
}
