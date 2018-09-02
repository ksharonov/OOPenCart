<?php

use yii\db\Migration;

/**
 * Class m180412_045315_insert_extension
 */
class m180412_045315_insert_extension extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('extension', [
            'title' => 'Доставка транспортной компанией',
            'name' => 'delivery_transport_company_extensions',
            'type' => \app\models\db\Extension::EXTENSION_TYPE_DELIVERY,
            'status' => 1,
            'params' => '[]',
            'class' => 'app\\extensions\\delivery\\DeliveryTransportCompanyExtension\\DeliveryTransportCompanyExtension',
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
        echo "m180412_045315_insert_extension cannot be reverted.\n";

        return false;
    }
    */
}
