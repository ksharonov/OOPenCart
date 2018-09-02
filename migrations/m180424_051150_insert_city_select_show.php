<?php

use yii\db\Migration;

/**
 * Class m180424_051150_insert_city_select_show
 */
class m180424_051150_insert_city_select_show extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'WIDGET.CITY.SELECT.SHOW',
            'setValue' => 1,
            'type' => app\models\db\Setting::TYPE_TEXT,
            'title' => 'Показывать виджет города'
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
        echo "m180424_051150_insert_city_select_show cannot be reverted.\n";

        return false;
    }
    */
}
