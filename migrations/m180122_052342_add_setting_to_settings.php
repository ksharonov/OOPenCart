<?php

use yii\db\Migration;

/**
 * Class m180122_052342_add_setting_to_settings
 */
class m180122_052342_add_setting_to_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->insert('setting', [
            'title' => 'Выбранный шаблон',
            'setKey' => 'TEMPLATE.SELECTED',
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
        echo "m180122_052342_add_setting_to_settings cannot be reverted.\n";

        return false;
    }
    */
}
