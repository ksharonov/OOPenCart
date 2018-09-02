<?php

use yii\db\Migration;

/**
 * Class m180525_094900_update_rows_in_extensions
 */
class m180525_094900_update_rows_in_extensions extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ext = \app\models\db\Extension::find()
            ->where('access IS NULL')
            ->all();

        foreach ($ext as $item) {
            $item->access = \app\models\db\Extension::ACCESS_GUEST;
            $item->save();
        }

        $ext = \app\models\db\Extension::find()
            ->where(['name' => 'payment_widget'])
            ->one();
        if ($ext) {
            $ext->status = \app\models\db\Extension::STATUS_NOT_ACTIVE;
            $ext->save();
        }

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
        echo "m180525_094900_update_rows_in_extensions cannot be reverted.\n";

        return false;
    }
    */
}
