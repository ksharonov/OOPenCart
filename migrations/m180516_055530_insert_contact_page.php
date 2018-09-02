<?php

use yii\db\Migration;

/**
 * Class m180516_055530_insert_contact_page
 */
class m180516_055530_insert_contact_page extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('page', [
            'title' => 'Контакты',
            'slug' => 'contacts',
            'content' => ''
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
        echo "m180516_055530_insert_contact_page cannot be reverted.\n";

        return false;
    }
    */
}
