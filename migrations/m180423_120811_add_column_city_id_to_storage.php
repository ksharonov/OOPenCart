<?php

use yii\db\Migration;

/**
 * Class m180423_120811_add_column_city_id_to_storage
 */
class m180423_120811_add_column_city_id_to_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage', 'cityId', $this->integer()->comment('Город'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage', 'cityId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180423_120811_add_column_city_id_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
