<?php

use yii\db\Migration;

/**
 * Class m180521_124010_insert_setting
 */
class m180521_124010_insert_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $statusId = \app\models\db\OrderStatus::findOne(['title' => 'Ошибка'])->id;

        $this->insert('setting', [
            'title' => 'Ошибка оплаты',
            'setKey' => 'ORDER.STATUS.PAID.ERROR',
            'setValue' => $statusId
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
        echo "m180521_124010_insert_setting cannot be reverted.\n";

        return false;
    }
    */
}
