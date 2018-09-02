<?php

use yii\db\Migration;

/**
 * Class m180525_090929_insert_isDefault_to_order_status
 */
class m180525_090929_insert_isDefault_to_order_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order_status', 'isHidden', $this->integer()->comment('Скрытый'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order_status', 'isHidden');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180525_090929_insert_isDefault_to_order_status cannot be reverted.\n";

        return false;
    }
    */
}
