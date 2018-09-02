<?php

use yii\db\Migration;

/**
 * Handles adding params to table `product_attribute_alias_onec`.
 */
class m180521_065001_add_params_column_to_product_attribute_alias_onec_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('product_attribute_alias_onec', 'params', 'json DEFAULT NULL');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('product_attribute_alias_onec', 'params');
    }
}
