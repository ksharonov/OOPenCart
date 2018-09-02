<?php

use yii\db\Migration;

/**
 * Class m180208_052124_add_type_to_price_group
 */
class m180208_052124_add_type_to_price_group extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->truncateTable('product_price_group');

        $this->addColumn('product_price_group', 'isDefault', $this->boolean());

        foreach (\app\models\db\ProductPriceGroup::$priorities as $priority => $title) {
            $this->insert('product_price_group', [
                'title' => $title,
                'priority' => $priority,
                'isDefault' => true
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_price_group', 'isDefault');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180208_052124_add_type_to_price_group cannot be reverted.\n";

        return false;
    }
    */
}
