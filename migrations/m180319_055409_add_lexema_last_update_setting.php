<?php

use yii\db\Migration;

/**
 * Class m180319_055409_add_lexema_last_update_setting
 */
class m180319_055409_add_lexema_last_update_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Время последней проверки обновлений из Лексемы',
            'setKey' => 'LEXEMA.LAST.UPDATE',
            'setValue' => '1521438602',
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180319_055409_add_lexema_last_update_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180319_055409_add_lexema_last_update_setting cannot be reverted.\n";

        return false;
    }
    */
}
