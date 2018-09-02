<?php

use yii\db\Migration;

/**
 * Class m180426_043421_add_show_setting_to_re_call_widget
 */
class m180426_043421_add_show_setting_to_re_call_widget extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'WIDGET.RECALL.SHOW',
            'setValue' => 1,
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Показ блока "Обратная связь"'
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
        echo "m180426_043421_add_show_setting_to_re_call_widget cannot be reverted.\n";

        return false;
    }
    */
}
