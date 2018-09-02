<?php

use yii\db\Migration;

/**
 * Class m180419_063039_insert_onec_authorisation_in_table_setting
 */
class m180419_063039_insert_onec_authorisation_in_table_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Параметры для авторизации 1С (Логин:Пароль)',
            'setKey' => 'ONEC.AUTH',
            'setValue' => 'Усатов Виктор Петрович:614889',
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180419_063039_insert_onec_authorisation_in_table_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180419_063039_insert_onec_authorisation_in_table_setting cannot be reverted.\n";

        return false;
    }
    */
}
