<?php

use yii\db\Migration;

/**
 * Class m180404_044024_add_address_indexes
 */
class m180404_044024_add_address_indexes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex('idx-address-relModel', 'address', 'relModel');
        $this->createIndex('idx-address-relModelId', 'address', 'relModelId');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('idx-address-relModel', 'address');
        $this->dropIndex('idx-address-relModelId', 'address');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180404_044024_add_address_indexes cannot be reverted.\n";

        return false;
    }
    */
}
