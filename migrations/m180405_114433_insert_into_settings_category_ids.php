<?php

use yii\db\Migration;

/**
 * Class m180405_114433_insert_into_settings_category_ids
 */
class m180405_114433_insert_into_settings_category_ids extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $newCategory = \app\models\db\ProductCategory::find()
            ->where(['title' => 'Новинки'])
            ->one();

        $this->insert('setting', [
                'setKey' => 'PRODUCT.LIST.NEW.CATEGORY.ID',
                'setValue' => $newCategory->id,
                'type' => 0,
                'title' => 'ID категории продуктов - Новинки',
                'params' => '[{"title":"","key":"Имя класса","value":"app\\\models\\\ProductCategory"}]'
            ]
        );

        $mainCategory = \app\models\db\ProductCategory::find()
            ->where(['title' => 'На главной'])
            ->one();

        $this->insert('setting', [
                'setKey' => 'PRODUCT.LIST.MAIN.CATEGORY.ID',
                'setValue' => $mainCategory->id,
                'type' => 0,
                'title' => 'ID категории продуктов - На главной',
                'params' => '[{"title":"","key":"Имя класса","value":"app\\\models\\\ProductCategory"}]'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180405_114433_insert_into_settings_category_ids cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_114433_insert_into_settings_category_ids cannot be reverted.\n";

        return false;
    }
    */
}
