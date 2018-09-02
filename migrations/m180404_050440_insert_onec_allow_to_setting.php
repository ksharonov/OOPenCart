<?php

use yii\db\Migration;

/**
 * Class m180404_050440_insert_onec_allow_to_setting
 */
class m180404_050440_insert_onec_allow_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Отправка данных в Лексему (вкл/выкл)',
            'setKey' => 'LEXEMA.ALLOW.SEND',
            'setValue' => '1',
            'type' => \app\models\db\Setting::TYPE_SELECT
        ]);
        $this->insert('setting', [
            'title' => 'Отправка данных в 1C (вкл/выкл)',
            'setKey' => 'ONEC.ALLOW.SEND',
            'setValue' => '0',
            'type' => \app\models\db\Setting::TYPE_SELECT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180404_050440_insert_onec_allow_to_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180404_050440_insert_onec_allow_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
