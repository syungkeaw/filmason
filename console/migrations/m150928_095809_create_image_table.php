<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_095809_create_image_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'movie_id' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%image}}');
    }
}
