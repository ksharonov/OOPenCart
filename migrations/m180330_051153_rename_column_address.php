<?php

use yii\db\Migration;

/**
 * Class m180330_051153_rename_column_address
 */
class m180330_051153_rename_column_address extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('address', 'coutryId', 'countryId');
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
        echo "m180330_051153_rename_column_address cannot be reverted.\n";

        return false;
    }
    */
}
