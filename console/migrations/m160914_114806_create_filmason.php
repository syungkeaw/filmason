<?php

use yii\db\Schema;
use yii\db\Migration;

class m160914_114806_create_filmason extends Migration
{
    public function up()
    {
        $this->createTable('{{%filmason}}', [
            'id' => $this->primaryKey(),
            'imdb_id' => $this->string()->notNull(),
            'vote' => $this->string()->notNull(),
            'voted_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%filmason}}');
    }
}
