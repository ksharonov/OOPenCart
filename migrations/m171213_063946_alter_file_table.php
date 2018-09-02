<?php

use yii\db\Migration;

/**
 * Class m171213_063946_alter_file_table
 */
class m171213_063946_alter_file_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameTable('product_file', 'file');
        $this->addColumn('file', 'relModel', $this->integer());
        $this->renameColumn('file', 'productId', 'relModelId');

        $this->alterColumn('file', 'relModel', $this->integer()->comment('Связанная модель'));
        $this->alterColumn('file', 'relModelId', $this->integer()->comment('id связанной модели'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('file', 'relModelId', 'productId');
        $this->dropColumn('file', 'relModel');
        $this->alterColumn('file', 'productId', $this->integer()->comment('id продукта'));
        $this->renameTable('file', 'product_file');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171213_063946_alter_file_table cannot be reverted.\n";

        return false;
    }
    */
}
