<?php

use yii\db\Migration;

/**
 * Class m180510_110531_insert_into_order_status_and_setting
 */
class m180510_110531_insert_into_order_status_and_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
    	$this->insert('order_status', [
    		'title' => 'Ошибка',
			'type' => 0,
		]);

    	$this->insert('setting', [
			'setKey' => 'ORDER.STATUS.ERROR',
			'setValue' => 5,
			'type' => app\models\db\Setting::TYPE_SELECT,
			'title' => 'Статус заказа: Ошибка'
		]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180510_110531_insert_into_order_status_and_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180510_110531_insert_into_order_status_and_setting cannot be reverted.\n";

        return false;
    }
    */
}
