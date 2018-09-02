<?php

use yii\db\Migration;

/**
 * Class m171213_050705_add_columns_to_user
 */
class m171213_050705_add_columns_to_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('user', 'fio', $this->string(256));
        $this->addColumn('user', 'phone', $this->integer());
        $this->addColumn('user', 'params', 'json DEFAULT NULL');

        $this->createIndex('user-fio', 'user', 'fio');
        $this->createIndex('user-phone', 'user', 'phone');


        $this->addColumn('client', 'phone', $this->integer());
        $this->addColumn('client', 'params', 'json DEFAULT NULL');
        $this->createIndex('client-phone', 'client', 'phone');

        $this->addColumn('address', 'type', $this->integer());
        $this->createIndex('address-type', 'address', 'type');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'fio');
        $this->dropColumn('user', 'phone');
        $this->dropColumn('user', 'params');

        $this->dropColumn('client', 'phone');
        $this->dropColumn('client', 'params');

        $this->dropColumn('address', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171213_050705_add_columns_to_user cannot be reverted.\n";

        return false;
    }
    */
}
