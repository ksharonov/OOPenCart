<?php

use yii\db\Migration;

/**
 * Class m180416_060420_insert_order_cancel_to_setting
 */
class m180416_060420_insert_order_cancel_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'ORDER.STATUS.CANCELED',
            'setValue' => 1,
            'type' => \app\models\db\Setting::TYPE_SELECT,
            'title' => 'Статус заказа: Отменён',
            'params' => '[{"title":"","key":"Имя класса","value":"app\\\models\\\OrderStatus"}]'
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
        echo "m180416_060420_insert_order_cancel_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
