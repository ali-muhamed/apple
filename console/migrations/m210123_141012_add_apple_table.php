<?php

use yii\db\Migration;

/**
 * Class m210123_141012_add_apple_table
 */
class m210123_141012_add_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('apple', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'fallen_at' => $this->integer(),
            'status' => $this->integer()->notNull(),
            'eaten' => $this->float()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('apple');
    }

}
