<?php

use yii\db\Migration;

/**
 * Class m180118_044556_add_settings_to_setting
 */
class m180118_044556_add_settings_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Статус заказа: В работе',
            'setKey' => 'ORDER.STATUS.PROCESS',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_SELECT
        ]);

        $this->insert('setting', [
            'title' => 'Статус заказа: Завершён',
            'setKey' => 'ORDER.STATUS.FINISH',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_SELECT
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
        echo "m180118_044556_add_settings_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
