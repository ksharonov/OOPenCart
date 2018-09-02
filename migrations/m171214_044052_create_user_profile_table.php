<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 */
class m171214_044052_create_user_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_profile', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->comment('Пользователь'),
            'compareData' => 'json DEFAULT NULL',
            'favoriteData' => 'json DEFAULT NULL',
            'cartData' => 'json DEFAULT NULL'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_profile');
    }
}
