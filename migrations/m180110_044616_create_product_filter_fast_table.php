<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_filter_fast`.
 */
class m180110_044616_create_product_filter_fast_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_filter_fast', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->comment('Название быстрого фильтра'),
            'name' => $this->string(64)->comment('Имя фильтра'),
            'categoryId' => $this->integer()->comment('Категория фильтров'),
            'expanded' => $this->integer()->comment('Вес в списке')
        ]);

        $this->createTable('product_filter_fast_param', [
            'id' => $this->primaryKey(),
            'productFilterFastId' => $this->integer()->comment('Быстрый фильтр'),
            'attributeId' => $this->integer()->comment('Атрибут'),
            'attrValue' => $this->char(255)->comment('Значение атрибута')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_filter_fast');
        $this->dropTable('product_filter_fast_param');
    }
}
