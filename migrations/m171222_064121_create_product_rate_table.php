<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_rate`.
 */
class m171222_064121_create_product_rate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('product_review', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->comment('Заголовок'),
            'content' => $this->text()->comment('Содержимое'),
            'productId' => $this->integer()->comment('Продукт'),
            'userId' => $this->integer()->comment('Пользователь'),
            'rating' => $this->integer(1)->comment('Оценка товара'),
            'dtCreate' => $this->timestamp()->comment('Дата создания')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('product_review');
    }
}
