<?php

use yii\db\Migration;

/**
 * Class m171211_062826_add_description_to_unit
 */
class m171211_062826_add_description_to_unit extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('unit', 'description', $this->string(128));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('unit', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171211_062826_add_description_to_unit cannot be reverted.\n";

        return false;
    }
    */
}
