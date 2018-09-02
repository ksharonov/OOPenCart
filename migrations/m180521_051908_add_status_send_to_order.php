<?php

use yii\db\Migration;

/**
 * Class m180521_051908_add_status_send_to_order
 */
class m180521_051908_add_status_send_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('order_status', [
            'title' => 'Отправлена',
            'type' => \app\models\db\OrderStatus::TYPE_ORDER
        ]);

        $statusId = \app\models\db\OrderStatus::findOne(['title' => 'Отправлена'])->id;

        $this->insert('setting', [
            'title' => 'Статус заказа: Отправлен',
            'setKey' => 'ORDER.STATUS.SEND',
            'setValue' => $statusId
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
        echo "m180521_051908_add_status_send_to_order cannot be reverted.\n";

        return false;
    }
    */
}
