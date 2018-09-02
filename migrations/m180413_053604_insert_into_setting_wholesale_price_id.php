<?php

use yii\db\Migration;

/**
 * Class m180413_053604_insert_into_setting_wholesale_price_id
 */
class m180413_053604_insert_into_setting_wholesale_price_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'WHOLESALE.PRICE.ID',
            'setValue' => '11',
            'type' => 0,
            'title' => 'Оптовая цена',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('setting', ['setKey' => 'WHOLESALE.PRICE.ID']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_053604_insert_into_setting_wholesale_price_id cannot be reverted.\n";

        return false;
    }
    */
}
