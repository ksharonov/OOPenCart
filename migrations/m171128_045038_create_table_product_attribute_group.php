<?php

use yii\db\Migration;

/**
 * Class m171128_045038_create_table_product_attribute_group
 */
class m171128_045038_create_table_product_attribute_group extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_attribute_group', [
            'id' => $this->primaryKey()->comment('id группы атрибута'),
            'title' => $this->string(255)->notNull()->comment('Заголовок группы')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_attribute_group');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_045038_create_table_product_attribute_group cannot be reverted.\n";

        return false;
    }
    */
}
