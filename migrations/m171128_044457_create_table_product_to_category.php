<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m171128_044457_create_table_product_to_category
 */
class m171128_044457_create_table_product_to_category extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('post_category', 'name', 'title');

        $this->addColumn('product', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->addColumn('product', 'dtCreate', $this->dateTime()->defaultValue(new Expression('NOW()')));

        $this->addColumn('product_category', 'dtUpdate', 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->addColumn('product_category', 'dtCreate', $this->dateTime()->defaultValue(new Expression('NOW()')));

        $this->createTable('product_to_category', [
            'id' => $this->primaryKey()->comment('id связи'),
            'productId' => $this->char(255)->comment('id продукта'),
            'categoryId' => $this->text()->comment('id категории'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('post_category', 'title', 'name');

        $this->dropTable('product_to_category');

        $this->dropColumn('product', 'dtUpdate');
        $this->dropColumn('product', 'dtCreate');

        $this->dropColumn('product_category', 'dtUpdate');
        $this->dropColumn('product_category', 'dtCreate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_044457_create_table_product_to_category cannot be reverted.\n";

        return false;
    }
    */
}
