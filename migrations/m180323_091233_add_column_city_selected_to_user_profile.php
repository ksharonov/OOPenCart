<?php

use yii\db\Migration;

/**
 * Class m180323_091233_add_column_city_selected_to_user_profile
 */
class m180323_091233_add_column_city_selected_to_user_profile extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user_profile' , 'citySelected', $this->integer(11));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user_profile', 'citySelected');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180323_091233_add_column_city_selected_to_user_profile cannot be reverted.\n";

        return false;
    }
    */
}
