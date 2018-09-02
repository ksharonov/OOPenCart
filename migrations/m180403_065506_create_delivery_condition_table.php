<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery_condition`.
 */
class m180403_065506_create_delivery_condition_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('delivery_condition', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('delivery_condition');
    }
}
