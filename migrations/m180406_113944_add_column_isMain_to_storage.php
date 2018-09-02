<?php

use yii\db\Migration;

/**
 * Class m180406_113944_add_column_isMain_to_storage
 */
class m180406_113944_add_column_isMain_to_storage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('storage', 'isMain', $this->integer(1)->comment('Основной магазин'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('storage', 'isMain');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180406_113944_add_column_isMain_to_storage cannot be reverted.\n";

        return false;
    }
    */
}
