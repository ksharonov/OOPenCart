<?php

use yii\db\Migration;

/**
 * Class m171207_113424_add_default_price
 */
class m171207_113424_add_default_price extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->truncateTable('product_price_group');
        $this->insert('product_price_group', [
                'title' => 'Основной'
            ]
        );

        $this->insert('setting', [
            'setKey' => 'DEFAULT.PRICE.ID',
            'setValue' => 1
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable('product_price_group');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171207_113424_add_default_price cannot be reverted.\n";

        return false;
    }
    */
}
