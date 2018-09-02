<?php

use yii\db\Migration;

/**
 * Class m180409_045459_insert_into_product_category_sales
 */
class m180409_045459_insert_into_product_category_sales extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('product_category', [
            'title' => 'Распродажа',
            'parentId' => -30,
        ]);

        $salesCategory = \app\models\db\ProductCategory::find()
            ->where(['title' => 'Распродажа'])
            ->one();

        $this->insert('setting', [
                'setKey' => 'PRODUCT.LIST.DISCOUNT.CATEGORY.ID',
                'setValue' => $salesCategory->id,
                'type' => 0,
                'title' => 'ID категории продуктов - Распродажа',
                'params' => '[{"title":"","key":"Имя класса","value":"app\\\models\\\ProductCategory"}]'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('product_category', ['title' => 'Распродажа']);
        $this->delete('setting', ['setKey' => 'PRODUCT.LIST.DISCOUNT.CATEGORY.ID']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180409_045459_insert_into_product_category_sales cannot be reverted.\n";

        return false;
    }
    */
}
