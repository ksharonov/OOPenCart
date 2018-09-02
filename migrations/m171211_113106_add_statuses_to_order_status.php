<?php

use yii\db\Migration;

/**
 * Class m171211_113106_add_statuses_to_order_status
 */
class m171211_113106_add_statuses_to_order_status extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->truncateTable('order_status');
        $this->insert('order_status', [
                'title' => 'Новый'
            ]
        );
        $this->insert('order_status', [
                'title' => 'В процессе'
            ]
        );
        $this->insert('order_status', [
                'title' => 'Завершен'
            ]
        );
        $this->insert('order_status', [
                'title' => 'Отменен'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable('order_status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171211_113106_add_statuses_to_order_status cannot be reverted.\n";

        return false;
    }
    */
}
