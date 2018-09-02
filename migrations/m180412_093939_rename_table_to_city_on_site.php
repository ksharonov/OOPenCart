<?php

use yii\db\Migration;

/**
 * Class m180412_093939_rename_table_to_city_on_site
 */
class m180412_093939_rename_table_to_city_on_site extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('cities_on_site', 'city_on_site');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameTable('city_on_site', 'cities_on_site');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_093939_rename_table_to_city_on_site cannot be reverted.\n";

        return false;
    }
    */
}
