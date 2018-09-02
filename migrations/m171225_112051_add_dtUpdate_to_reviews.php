<?php

use yii\db\Migration;

/**
 * Class m171225_112051_add_dtUpdate_to_reviews
 */
class m171225_112051_add_dtUpdate_to_reviews extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_review', 'dtUpdate', $this->timestamp()->comment('Дата обновления'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_review', 'dtUpdate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171225_112051_add_dtUpdate_to_reviews cannot be reverted.\n";

        return false;
    }
    */
}
