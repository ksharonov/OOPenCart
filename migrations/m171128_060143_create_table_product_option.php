<?php

use yii\db\Migration;

/**
 * Class m171128_060143_create_table_product_option
 */
class m171128_060143_create_table_product_option extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_option', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'type' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_option');
    }
}
