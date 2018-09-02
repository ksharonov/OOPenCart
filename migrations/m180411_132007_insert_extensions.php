<?php

use yii\db\Migration;

/**
 * Class m180411_132007_insert_extensions
 */
class m180411_132007_insert_extensions extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('extension', [
            'title' => 'Доставка на мой адрес',
            'name' => 'delivery_my_address_extensions',
            'type' => \app\models\db\Extension::EXTENSION_TYPE_DELIVERY,
            'status' => 1,
            'params' => '[]',
            'class' => 'app\\extensions\\delivery\\DeliveryMyAddressesExtension\\DeliveryMyAddressesExtension',
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
        echo "m180411_132007_insert_extensions cannot be reverted.\n";

        return false;
    }
    */
}
