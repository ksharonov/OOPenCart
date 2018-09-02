<?php

use yii\db\Migration;

/**
 * Class m180328_115004_add_column_back_code_to_product
 */
class m180328_115004_add_column_back_code_to_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'backCode', $this->string(20)->comment('Код магазина'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'backCode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180328_115004_add_column_back_code_to_product cannot be reverted.\n";

        return false;
    }
    */
}
