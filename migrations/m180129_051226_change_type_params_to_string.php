<?php

use yii\db\Migration;

/**
 * Class m180129_051226_change_type_params_to_string
 */
class m180129_051226_change_type_params_to_string extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('client', 'params', $this->text()->comment('Параметры'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('client', 'params', 'json');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180129_051226_change_type_params_to_string cannot be reverted.\n";

        return false;
    }
    */
}
