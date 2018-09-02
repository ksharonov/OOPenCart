<?php

use yii\db\Migration;

/**
 * Class m180529_111824_rename_status_title
 */
class m180529_111824_rename_status_title extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ordStatus = \app\models\db\OrderStatus::find()
            ->where(['title' => 'Ошибка оплаты'])
            ->one();
        if ($ordStatus) {
            $ordStatus->title = 'Не оплачен';
            $ordStatus->save();
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
        echo "m180529_111824_rename_status_title cannot be reverted.\n";

        return false;
    }
    */
}
