<?php

use yii\db\Migration;

/**
 * Class m171220_090617_add_user_address_to_order_and_product_to_order_content
 */
class m171220_090617_add_user_address_to_order_and_product_to_order_content extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'userData', $this->text()->comment('Данные пользователя'));
        $this->addColumn('order', 'addressData', $this->text()->comment('Данные адреса'));
        $this->addColumn('order_content', 'productData', $this->text()->comment('Данные продукта'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'userData');
        $this->dropColumn('order', 'addressData');
        $this->dropColumn('order_content', 'productData');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_090617_add_user_address_to_order_and_product_to_order_content cannot be reverted.\n";

        return false;
    }
    */
}
