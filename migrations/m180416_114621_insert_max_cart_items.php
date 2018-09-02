<?php

use yii\db\Migration;

/**
 * Class m180416_114621_insert_max_cart_items
 */
class m180416_114621_insert_max_cart_items extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'CART.MAX.COUNT.ITEMS',
            'setValue' => 50,
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Максимальное количество товаров в корзине',
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
        echo "m180416_114621_insert_max_cart_items cannot be reverted.\n";

        return false;
    }
    */
}
