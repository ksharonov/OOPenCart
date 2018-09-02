<?php

use yii\db\Migration;

/**
 * Class m180116_091621_add_settings_to_setting
 */
class m180116_091621_add_settings_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('setting', 'title', $this->string(128));
        $this->addColumn('setting', 'source', $this->text());

        $this->insert('setting', [
            'title' => 'Статус заказа: Новый',
            'setKey' => 'ORDER.STATUS.NEW',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_SELECT
        ]);

        $this->insert('setting', [
            'title' => 'Статус заказа: Оплачен',
            'setKey' => 'ORDER.STATUS.PAID',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_SELECT
        ]);

        $this->insert('setting', [
            'title' => 'Статус заказа: Доставлен',
            'setKey' => 'ORDER.STATUS.DELIVERED',
            'setValue' => '',
            'type' => \app\models\db\Setting::TYPE_SELECT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('setting', 'title');
        $this->dropColumn('setting', 'source');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180116_091621_add_settings_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
