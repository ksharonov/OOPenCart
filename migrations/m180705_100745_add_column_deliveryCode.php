<?php

use yii\db\Migration;

/**
 * Class m180705_100745_add_column_deliveryCode
 */
class m180705_100745_add_column_deliveryCode extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'deliveryCode', $this->integer()->comment('Код получения заказа')->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'deliveryCode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180705_100745_add_column_deliveryCode cannot be reverted.\n";

        return false;
    }
    */
}
