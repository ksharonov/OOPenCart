<?php

use yii\db\Migration;

/**
 * Class m180207_093409_add_order_finish_hours_config
 */
class m180207_093409_add_order_finish_hours_config extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Количество часов для готовности заказа',
            'setKey' => 'ORDER.PROCESS.HOURS',
            'setValue' => 4,
            'type' => \app\models\db\Setting::TYPE_TEXT
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
        echo "m180207_093409_add_order_finish_hours_config cannot be reverted.\n";

        return false;
    }
    */
}
