<?php

use yii\db\Migration;

/**
 * Class m180717_075734_add_days_reserve_to_setting
 */
class m180717_075734_add_days_reserve_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'ORDER.DAYS.RESERVED',
            'setValue' => 10,
            'type' => 0,
            'title' => 'Количество дней резервации заказа'
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
        echo "m180717_075734_add_days_reserve_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
