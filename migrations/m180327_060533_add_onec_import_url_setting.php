<?php

use yii\db\Migration;

/**
 * Class m180327_060533_add_onec_import_url_setting
 */
class m180327_060533_add_onec_import_url_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Адрес импорта данных из 1С',
            'setKey' => 'ONEC.IMPORT.URL',
            'setValue' => '192.168.10.71',
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180327_060533_add_onec_import_url_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180327_060533_add_onec_import_url_setting cannot be reverted.\n";

        return false;
    }
    */
}
