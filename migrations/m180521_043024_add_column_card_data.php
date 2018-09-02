<?php

use yii\db\Migration;

/**
 * Class m180521_043024_add_column_card_data
 */
class m180521_043024_add_column_card_data extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('order', 'cardData', $this->text()->comment('Инфа по скидочной карты'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'cardData');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180521_043024_add_column_card_data cannot be reverted.\n";

        return false;
    }
    */
}
