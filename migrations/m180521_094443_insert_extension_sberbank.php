<?php

use yii\db\Migration;

/**
 * Class m180521_094443_insert_extension_sberbank
 */
class m180521_094443_insert_extension_sberbank extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
//        $extId = \app\models\db\Extension::find()
//            ->where(['name' => 'online_payment_extension'])
//            ->one();

        $this->insert('extension', [
            'title' => 'Сбербанк Онлайн',
            'name' => 'sberbank_payment_widget',
            'class' => 'app\\extensions\\payment\\SberbankPaymentExtension\\SberbankPaymentExtension',
            'type' => 0,
            'status' => 1,
            'params' => '{"url": "https://3dsec.sberbank.ru/payment/rest", "login": "220v2_energosi-api", "password": "220v2_energosi"}',
//            'parentId' => $extId->id
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
        echo "m180521_094443_insert_extension_sberbank cannot be reverted.\n";

        return false;
    }
    */
}
