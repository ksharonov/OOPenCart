<?php

use yii\db\Migration;

/**
 * Class m171219_094850_add_params_to_produc
 */
class m171219_094850_add_params_to_produc extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'params', 'json DEFAULT NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'params');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171219_094850_add_params_to_produc cannot be reverted.\n";

        return false;
    }
    */
}
