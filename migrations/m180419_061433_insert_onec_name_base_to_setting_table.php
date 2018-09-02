<?php

use yii\db\Migration;

/**
 * Class m180419_061433_insert_onec_name_base_to_setting_table
 */
class m180419_061433_insert_onec_name_base_to_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Название подключаемой базы 1С',
            'setKey' => 'ONEC.NAME.BASE',
            'setValue' => 'InfoBase2',
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180419_061433_insert_onec_name_base_to_setting_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_061433_insert_onec_name_base_to_setting_table cannot be reverted.\n";

        return false;
    }
    */
}
