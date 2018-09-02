<?php

use yii\db\Migration;

/**
 * Class m180324_090034_create_table_order_lexema
 */
class m180324_090034_create_table_order_lexema extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('order_lexema', [
            'id' => $this->primaryKey(),
            'orderNumber' => $this->string(128)->comment('Лексемовский номер заказа'),
            'orderId' => $this->integer(11)->comment('ID заказа из order')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('order_lexema');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180324_090034_create_table_order_lexema cannot be reverted.\n";

        return false;
    }
    */
}
