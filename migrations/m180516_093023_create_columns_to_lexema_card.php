<?php

use yii\db\Migration;

/**
 * Class m180516_093023_create_columns_to_lexema_card
 */
class m180516_093023_create_columns_to_lexema_card extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('lexema_card', 'userId');
        $this->addColumn('lexema_card', 'fio', $this->string(256)->comment('ФИО Владельца'));
        $this->addColumn('lexema_card', 'phone', $this->integer()->comment('Телефон'));
        $this->alterColumn('lexema_card', 'bonuses', $this->float());
        $this->alterColumn('lexema_card', 'amountPurchases', $this->float());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('lexema_card', 'userId', $this->integer());
        $this->dropColumn('lexema_card', 'fio');
        $this->dropColumn('lexema_card', 'phone');
        $this->alterColumn('lexema_card', 'bonuses', $this->integer());
        $this->alterColumn('lexema_card', 'amountPurchases', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180516_093023_create_columns_to_lexema_card cannot be reverted.\n";

        return false;
    }
    */
}
