<?php

use yii\db\Migration;

/**
 * Class m180424_051555_insert_clear_cart_param
 */
class m180424_051555_insert_clear_cart_param extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'CART.CLEAR.AFTER.LOGOUT',
            'setValue' => 0,
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Очистка корзины после "Выхода из профиля"'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180424_051555_insert_clear_cart_param cannot be reverted.\n";

        return false;
    }
    */
}
