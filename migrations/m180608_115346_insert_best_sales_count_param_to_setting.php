<?php

use yii\db\Migration;

/**
 * Class m180608_115346_insert_best_sales_count_param_to_setting
 */
class m180608_115346_insert_best_sales_count_param_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Количество продаж для попадания в Хиты Продаж',
            'setKey' => 'BEST.SALES.COUNT',
            'setValue' => 5
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
        echo "m180608_115346_insert_best_sales_count_param_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
