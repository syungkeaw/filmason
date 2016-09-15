<?php

use yii\db\Schema;
use yii\db\Migration;

class m150928_101335_create_people_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%people}}', [
            'id' => $this->primaryKey(),
            'tmdb_id' => $this->integer()->notNull()->unique(),
            'full_name' => $this->string(),
            'image_path' => $this->string(),
            'type' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('{{%people}}');
    }
}
