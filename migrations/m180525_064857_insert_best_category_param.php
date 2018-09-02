<?php

use yii\db\Migration;

/**
 * Class m180525_064857_insert_best_category_param
 */
class m180525_064857_insert_best_category_param extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $pc = new \app\models\db\ProductCategory();
        $pc->title = 'Хиты продаж';
        $pc->isDefault = true;
        $pc->save();


        $this->insert('setting', [
            'title' => 'ID категории товаров поставщиков',
            'setKey' => 'PRODUCT.LIST.BEST.CATEGORY.ID',
            'setValue' => $pc->id
        ]);
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
        echo "m180525_064857_insert_best_category_param cannot be reverted.\n";

        return false;
    }
    */
}
