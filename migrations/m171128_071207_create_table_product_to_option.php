<?php

use yii\db\Migration;

/**
 * Class m171128_071207_create_table_product_to_option
 */
class m171128_071207_create_table_product_to_option extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_to_option', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull()->comment('Ид товара'),
            'optionId' => $this->integer()->notNull()->comment('Ид опции'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_to_option');
    }
}
