<?php

use yii\db\Migration;

/**
 * Class m180525_085816_insert_statuses_to_status
 */
class m180525_085816_insert_statuses_to_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('order_status', [
            'title' => 'Оплачен',
            'type' => 0
        ]);

        $this->insert('order_status', [
            'title' => 'Ошибка оплаты',
            'type' => 0
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
        echo "m180525_085816_insert_statuses_to_status cannot be reverted.\n";

        return false;
    }
    */
}
