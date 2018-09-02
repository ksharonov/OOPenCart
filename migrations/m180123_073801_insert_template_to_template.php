<?php

use yii\db\Migration;

/**
 * Class m180123_073801_add_template_to_template
 */
class m180123_073801_insert_template_to_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->truncateTable('template');

        $this->insert('template', [
            'name' => 'test_energosi_template',
            'class' => 'templates\\TestEnergosiTemplate\\UsatovTemplate',
            'title' => 'Тестовый шаб'
        ]);

        \app\models\db\Setting::set('TEMPLATE.SELECTED', 1);
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
        echo "m180123_073801_add_template_to_template cannot be reverted.\n";

        return false;
    }
    */
}
