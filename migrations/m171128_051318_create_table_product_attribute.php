<?php

use yii\db\Migration;

/**
 * Class m171128_051318_create_table_product_attribute
 */
class m171128_051318_create_table_product_attribute extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_attribute', [
            'id' => $this->primaryKey()->comment('id атрибута'),
            'title' => $this->string(255)->notNull()->comment('Заголовок атрибута'),
            'attributeGroupId' => $this->integer()->notNull()->comment('id группы атрибута')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_attribute');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_051318_create_table_product_attribute cannot be reverted.\n";

        return false;
    }
    */
}
