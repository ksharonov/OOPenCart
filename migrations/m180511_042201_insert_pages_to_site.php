<?php

use yii\db\Migration;

/**
 * Class m180511_042201_insert_pages_to_site
 */
class m180511_042201_insert_pages_to_site extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('page', [
            'title' => 'Покупателям',
            'slug' => 'customer',
            'content' => ''
        ]);

        $this->insert('page', [
            'title' => 'О компании',
            'slug' => 'about'
        ]);

        $this->insert('page', [
            'title' => 'Производство',
            'slug' => 'production'
        ]);

        $this->insert('page', [
            'title' => 'Акции',
            'slug' => 'offers'
        ]);

        $this->insert('page', [
            'title' => 'Оплата',
            'slug' => 'payment'
        ]);

        $this->insert('page', [
            'title' => 'Доставка',
            'slug' => 'delivery'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        \app\models\db\Page::deleteAll([
            'slug' => [
                'customer',
                'about',
                'production',
                'offers',
                'payment',
                'delivery'
            ]
        ]);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180511_042201_insert_pages_to_site cannot be reverted.\n";

        return false;
    }
    */
}
