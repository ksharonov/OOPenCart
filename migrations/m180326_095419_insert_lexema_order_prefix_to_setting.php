<?php

use yii\db\Migration;

/**
 * Class m180326_095419_insert_lexema_order_prefix_to_setting
 */
class m180326_095419_insert_lexema_order_prefix_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Префикс номера заказа Лексемы',
            'setKey' => 'LEXEMA.ORDER.PREFIX',
            'setValue' => 'A',
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180326_095419_insert_lexema_order_prefix_to_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180326_095419_insert_lexema_order_prefix_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
