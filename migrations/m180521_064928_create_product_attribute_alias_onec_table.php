<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_attribute_alias_onec`.
 */
class m180521_064928_create_product_attribute_alias_onec_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_attribute_alias_onec', [
            'id' => $this->primaryKey()->comment('id атрибута'),
            //'title' => $this->string(255)->notNull()->comment('Заголовок атрибута'),
            'attributeId' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_attribute_alias_onec');
    }
}
