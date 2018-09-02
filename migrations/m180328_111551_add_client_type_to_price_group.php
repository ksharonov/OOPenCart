<?php

use yii\db\Migration;

/**
 * Class m180328_111551_add_client_type_to_price_group
 */
class m180328_111551_add_client_type_to_price_group extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_price_group', 'clientType', $this->integer()->comment('Тип клиента'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_price_group', 'clientType');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180328_111551_add_client_type_to_price_group cannot be reverted.\n";

        return false;
    }
    */
}
