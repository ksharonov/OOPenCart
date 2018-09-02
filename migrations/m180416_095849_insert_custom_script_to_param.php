<?php

use yii\db\Migration;

/**
 * Class m180416_095849_insert_custom_script_to_param
 */
class m180416_095849_insert_custom_script_to_param extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'SITE.HEADER.SCRIPT',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Строка с кастомным скриптом в header'
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
        echo "m180416_095849_insert_custom_script_to_param cannot be reverted.\n";

        return false;
    }
    */
}
