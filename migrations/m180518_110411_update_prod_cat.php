<?php

use yii\db\Migration;

/**
 * Class m180518_110411_update_prod_cat
 */
class m180518_110411_update_prod_cat extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('product_category', ['status' => '1'], 'status IS NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180518_110411_update_prod_cat cannot be reverted.\n";

        return false;
    }
    */
}
