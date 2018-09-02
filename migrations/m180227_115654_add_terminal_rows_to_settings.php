<?php

use yii\db\Migration;

/**
 * Class m180227_115654_add_terminal_rows_to_settings
 */
class m180227_115654_add_terminal_rows_to_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Статус терминала(вкл/выкл)',
            'setKey' => 'TERMINAL.STATUS',
            'setValue' => 0,
            'type' => \app\models\db\Setting::TYPE_SELECT,
        ]);

        $this->insert('setting', [
            'title' => 'Клиент, от имени которого работает терминал',
            'setKey' => 'TERMINAL.CLIENT',
            'setValue' => 1,
            'type' => \app\models\db\Setting::TYPE_SELECT,
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('setting', ['setKey' => 'TERMINAL.STATUS']);
        $this->delete('setting', ['setKey' => 'TERMINAL.CLIENT']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_115654_add_terminal_rows_to_settings cannot be reverted.\n";

        return false;
    }
    */
}
