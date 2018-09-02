<?php

use yii\db\Migration;

/**
 * Class m171225_105158_add_columns_to_product_review
 */
class m171225_105158_add_columns_to_product_review extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('product_review', 'content', 'comment');
        $this->addColumn('product_review', 'positive', $this->text()->comment('Положительное'));
        $this->addColumn('product_review', 'negative', $this->text()->comment('Отрицательное'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('product_review', 'comment', 'content');
        $this->dropColumn('product_review', 'positive');
        $this->dropColumn('product_review', 'negative');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171225_105158_add_columns_to_product_review cannot be reverted.\n";

        return false;
    }
    */
}
