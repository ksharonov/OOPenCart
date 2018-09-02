<?php

use yii\db\Migration;

/**
 * Class m171220_092935_add_client_to_order
 */
class m171220_092935_add_client_to_order extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'clientData', $this->text()->comment('Данные клиента'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'clientData');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_092935_add_client_to_order cannot be reverted.\n";

        return false;
    }
    */
}
