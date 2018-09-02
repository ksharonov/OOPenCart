<?php

use yii\db\Migration;

/**
 * Class m180427_113438_insert_terminal_name_onec_to_setting_table
 */
class m180427_113438_insert_terminal_name_onec_to_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Название терминала ККМ для создания чеков ККМ',
            'setKey' => 'TERMINAL.NAME.ONEC',
            'setValue' => 'Мега Тест',
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
        echo "m180427_113438_insert_terminal_name_onec_to_setting_table cannot be reverted.\n";

        return false;
    }
    */
}
