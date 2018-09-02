<?php

use yii\db\Migration;

/**
 * Class m180518_105628_add_status_to_product_category
 */
class m180518_105628_add_status_to_product_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_category', 'status', $this->integer()->comment('Статус'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_category', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180518_105628_add_status_to_product_category cannot be reverted.\n";

        return false;
    }
    */
}
