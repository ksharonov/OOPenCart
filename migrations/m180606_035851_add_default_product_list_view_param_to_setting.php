<?php

use yii\db\Migration;

/**
 * Class m180606_035851_add_default_product_list_view_param_to_setting
 */
class m180606_035851_add_default_product_list_view_param_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'PRODUCT.LIST.DEFAULT.VIEW',
            'setValue' => 'card',
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Список товаров: Вид по умолчанию (list, card)'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180606_035851_add_default_product_list_view_param_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
