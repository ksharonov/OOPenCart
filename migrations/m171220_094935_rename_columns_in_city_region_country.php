<?php

use yii\db\Migration;

/**
 * Class m171220_094935_rename_columns_in_city_region_country
 */
class m171220_094935_rename_columns_in_city_region_country extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        //city
        $this->renameColumn('city', 'id_city', 'id');
        $this->renameColumn('city', 'id_region', 'regionId');
        $this->renameColumn('city', 'id_country', 'countryId');
        $this->renameColumn('city', 'name', 'title');

        //country
        $this->renameColumn('country', 'id_country', 'id');
        $this->renameColumn('country', 'name', 'title');

        //region
        $this->renameColumn('region', 'id_region', 'id');
        $this->renameColumn('region', 'id_country', 'countryId');
        $this->renameColumn('region', 'name', 'title');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //city
        $this->renameColumn('city', 'id', 'id_city');
        $this->renameColumn('city', 'regionId', 'id_region');
        $this->renameColumn('city', 'countryId', 'id_country');
        $this->renameColumn('city', 'title', 'name');

        //country
        $this->renameColumn('country', 'id', 'id_country');
        $this->renameColumn('country', 'title', 'name');

        //region
        $this->renameColumn('region', 'id', 'id_region');
        $this->renameColumn('region', 'countryId', 'id_country');
        $this->renameColumn('region', 'title', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_094935_rename_columns_in_city_region_country cannot be reverted.\n";

        return false;
    }
    */
}
