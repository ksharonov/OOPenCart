<?php

use yii\db\Migration;

/**
 * Class m171220_094036_rename_columns_in_table_address
 */
class m171220_094036_rename_columns_in_table_address extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('address', 'country', 'coutryId');
        $this->renameColumn('address', 'region', 'regionId');
        $this->renameColumn('address', 'city', 'cityId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('address', 'countryId', 'coutry');
        $this->renameColumn('address', 'regionId', 'region');
        $this->renameColumn('address', 'cityId', 'city');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_094036_rename_columns_in_table_address cannot be reverted.\n";

        return false;
    }
    */
}
