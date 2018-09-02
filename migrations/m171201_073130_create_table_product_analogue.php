<?php

use yii\db\Migration;

/**
 * Class m171201_073130_create_table_product_analogue
 */
class m171201_073130_create_table_product_analogue extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_analogue', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer(),
            'productAnalogueId' => $this->integer(),
            'backcomp' => $this->boolean()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_analogue');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171201_073130_create_table_product_analogue cannot be reverted.\n";

        return false;
    }
    */
}
