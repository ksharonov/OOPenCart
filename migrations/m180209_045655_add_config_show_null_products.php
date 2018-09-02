<?php

use yii\db\Migration;

/**
 * Class m180209_045655_add_config_show_null_products
 */
class m180209_045655_add_config_show_null_products extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'PRODUCT.SHOW.NULL.BALANCE',
            'setValue' => '0',
            'type' => '1',
            'title' => 'Показывать товары, которые отсутствуют'
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
        echo "m180209_045655_add_config_show_null_products cannot be reverted.\n";

        return false;
    }
    */
}
