<?php

use yii\db\Migration;

/**
 * Class m171128_072705_create_table_product_file
 */
class m171128_072705_create_table_product_file extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('product_file', [
            'id' => $this->primaryKey(),
            'productId' => $this->integer()->notNull()->comment('Ид товара'),
            'path' => $this->string()->notNull()->comment('Путь до файла'),
            'type' => $this->integer()->notNull()->defaultValue(0)->comment('Тип файла'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус файла')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('product_file');

        return true;
    }
}
