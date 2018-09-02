<?php

use yii\db\Migration;

/**
 * Class m180518_122433_insert_set
 */
class m180518_122433_insert_set extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('product_category', [
            'title' => 'Продукция поставщиков',
            'parentId' => '-2'
        ]);

        $id = Yii::$app->db->getLastInsertID();

        $this->insert('setting', [
            'title' => 'ID категории товаров поставщиков',
            'setKey' => 'PRODUCT.PRODUCER.CATEGORY.ID',
            'setValue' => (string)$id
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
        echo "m180518_122433_insert_set cannot be reverted.\n";

        return false;
    }
    */
}
