<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m210123_123200_add_new_admin
 */
class m210123_123200_add_new_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'admin', $this->integer()->defaultValue(null));

        $user = \Yii::createObject(
            [
                'class' => User::class,
                'scenario' => 'create',
                'email' => 'admin@example.com',
                'username' => 'admin',
                'password' => 'admin',
                'admin' => '1',
            ]
        );

        if (!$user->insert(false)) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'admin');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210123_123200_add_new_admin cannot be reverted.\n";

        return false;
    }
    */
}
