<?php

use yii\db\Migration;

/**
 * Class m180213_103500_insert_setting_default_price_value
 */
class m180213_103500_insert_setting_default_price_value extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Стандартное значение цены для пустого товара',
            'setKey' => 'DEFAULT.PRICE.VALUE',
            'setValue' => 999999,
            'type' => \app\models\db\Setting::TYPE_TEXT
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
        echo "m180213_103500_insert_setting_default_price_value cannot be reverted.\n";

        return false;
    }
    */
}
