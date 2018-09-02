<?php

use yii\db\Migration;

/**
 * Class m180423_102359_add_blocks_and_setting
 */
class m180423_102359_add_blocks_and_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $contactPhoneBlock = \app\models\db\Block::findOne(['blockKey' => 'CONTACT.PHONE']);

        if (!$contactPhoneBlock) {
            $this->insert('block', [
                'title' => 'Номер телефона',
                'blockKey' => 'CONTACT.PHONE',
                'blockValue' => '',
                'type' => \app\models\db\Block::TYPE_RAW
            ]);
        }

        $this->insert('setting', [
            'setKey' => 'SITE.NAME',
            'setValue' => 'Название сайта',
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Название сайта(компании)',
        ]);

        $this->insert('setting', [
            'setKey' => 'SITE.DESCRIPTION',
            'setValue' => 'Описание сайта',
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Описание сайта',
        ]);

        $this->insert('setting', [
            'setKey' => 'SITE.LOGO.URL',
            'setValue' => '/img/header__logo.png',
            'type' => \app\models\db\Setting::TYPE_TEXT,
            'title' => 'Лого сайта сайта',
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
        echo "m180423_102359_add_blocks_and_setting cannot be reverted.\n";

        return false;
    }
    */
}
