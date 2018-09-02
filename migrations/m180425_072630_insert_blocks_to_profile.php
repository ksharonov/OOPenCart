<?php

use yii\db\Migration;

/**
 * Class m180425_072630_insert_blocks_to_profile
 */
class m180425_072630_insert_blocks_to_profile extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('block', [
            'title' => 'Описание  поля "Наименование" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.TITLE',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
        ]);
        $this->insert('block', [
            'title' => 'Описание поля "Телефон" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.PHONE',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
        ]);
        $this->insert('block', [
            'title' => 'Описание поля "Эл.Почта" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.EMAIL',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
        ]);
        $this->insert('block', [
            'title' => 'Описание поля "ИНН" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.INN',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
        ]);
        $this->insert('block', [
            'title' => 'Описание поля "КПП" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.KPP',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
        ]);
        $this->insert('block', [
            'title' => 'Описание поля "Юр.Адрес" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.ADDRESS',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
        ]);
        $this->insert('block', [
            'title' => 'Описание поля "Баланс" организации в личном кабинете',
            'blockKey' => 'DROPDOWN.PROFILE.CLIENT.BALANCE',
            'blockValue' => '',
            'type' => \app\models\db\Block::TYPE_DROPDOWN,
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
        echo "m180425_072630_insert_blocks_to_profile cannot be reverted.\n";

        return false;
    }
    */
}
