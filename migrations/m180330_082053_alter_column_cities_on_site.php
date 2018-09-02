<?php

use yii\db\Migration;

/**
 * Class m180330_082053_alter_column_cities_on_site
 */
class m180330_082053_alter_column_cities_on_site extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('cities_on_site', 'city_id', 'cityId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180330_082053_alter_column_cities_on_site cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180330_082053_alter_column_cities_on_site cannot be reverted.\n";

        return false;
    }
    */
}
