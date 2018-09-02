<?php

use yii\db\Migration;

/**
 * Class m171128_065254_create_table_product_option_value
 */
class m171128_065254_create_table_product_option_value extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_option_value', [
            'id' => $this->primaryKey()->comment('Ид значения опции'),
            'optionId' => $this->integer()->notNull()->comment('Ид опции'),
            'value' => $this->string(45)->notNull()->comment('Значение опции'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_option_value');

        return true;
    }
}
