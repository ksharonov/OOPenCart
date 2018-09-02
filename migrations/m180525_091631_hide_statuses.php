<?php

use yii\db\Migration;

/**
 * Class m180525_091631_hide_statuses
 */
class m180525_091631_hide_statuses extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $statuses = \app\models\db\OrderStatus::find()
            ->where(['title' => 'Отправлена'])
            ->orWhere(['title' => 'Ошибка оплаты'])
            ->orWhere(['title' => 'Оплачен'])
            ->orWhere(['title' => 'Ошибка'])
            ->all();

        foreach ($statuses as $status) {
            $status->isHidden = true;
            $status->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180525_091631_hide_statuses cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180525_091631_hide_statuses cannot be reverted.\n";

        return false;
    }
    */
}
