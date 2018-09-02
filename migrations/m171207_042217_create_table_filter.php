<?php

use yii\db\Migration;

/**
 * Class m171207_042217_create_table_filter
 */
class m171207_042217_create_table_filter extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_filter', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->comment('Название фильтра'),
            'source' => $this->integer()->comment('Тип источника'),
            'sourceId' => $this->integer()->comment('Поле источника'),
            'position' => $this->integer()->comment('Вес в списке'),
            'expanded' => $this->integer()->comment('Скрытость поля'),
            'params' => $this->text()->comment('Дополнительные параметры')
        ]);

        $this->createTable('product_filter_to_category', [
            'id' => $this->primaryKey(),
            'filterId' => $this->integer()->comment('Фильтр'),
            'productCategoryId' => $this->integer()->comment('Категория продукта')
        ]);


        $this->createIndex('filter-title', 'product_filter', 'title');
        $this->createIndex('filter-source', 'product_filter', 'source');
        $this->createIndex('filter-source-id', 'product_filter', 'sourceId');
        $this->createIndex('filter-source-search', 'product_filter', ['source', 'sourceId']);
        $this->createIndex('filter-prod-filter', 'product_filter_to_category', 'filterId');
        $this->createIndex('filter-prod-category', 'product_filter_to_category', 'productCategoryId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_filter');
        $this->dropTable('product_filter_to_category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171207_042217_create_table_filter cannot be reverted.\n";

        return false;
    }
    */
}
