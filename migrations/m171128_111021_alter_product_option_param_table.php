<?php

use yii\db\Migration;

/**
 * Class m171128_111021_alter_product_option_param_table
 */
class m171128_111021_alter_product_option_param_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('product_option_param', 'povs', 'json DEFAULT NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('product_option_param', 'povs', $this->text()->comment('Иды опций'));

        return true;
    }
}
