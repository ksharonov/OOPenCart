<?php

use yii\db\Migration;

/**
 * Class m180417_045450_insert_payments
 */
class m180417_045450_insert_payments extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('extension', [
            'title' => 'Оплата в магазине',
            'name' => 'shop_payment_extension',
            'class' => 'app\\extensions\\ShopPaymentExtension\\ShopPaymentExtension',
            'type' => \app\models\db\Extension::EXTENSION_TYPE_PAYMENT,
            'status' => \app\models\db\Extension::STATUS_ACTIVE
        ]);

        $this->insert('extension', [
            'title' => 'Онлайн',
            'name' => 'online_payment_extension',
            'class' => 'app\\extensions\\OnlinePaymentExtension\\OnlinePaymentExtension',
            'type' => \app\models\db\Extension::EXTENSION_TYPE_PAYMENT,
            'status' => \app\models\db\Extension::STATUS_ACTIVE
        ]);

        $this->insert('extension', [
            'title' => 'Безналичный расчёт',
            'name' => 'non_cash_payment_extension',
            'class' => 'app\\extensions\\NonCashPaymentExtension\\NonCashPaymentExtension',
            'type' => \app\models\db\Extension::EXTENSION_TYPE_PAYMENT,
            'status' => \app\models\db\Extension::STATUS_ACTIVE
        ]);
//
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
        echo "m180417_045450_insert_payments cannot be reverted.\n";

        return false;
    }
    */
}
