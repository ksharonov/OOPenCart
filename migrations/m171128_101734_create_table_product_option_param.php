<?php

use yii\db\Migration;

/**
 * Class m171128_101734_create_table_product_option_param
 */
class m171128_101734_create_table_product_option_param extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_option_param', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull()->comment('Ид товара'),
            'povs' => $this->text()->comment('Иды опций'),
            'qnt' => $this->string(50)->defaultValue(0)->comment('Количество'),
            'price' => $this->string(50)->defaultValue(0)->comment('Цена'),
            'weight' => $this->string(50)->defaultValue(0)->comment('Вес'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_option_param');

        return true;
    }
}
