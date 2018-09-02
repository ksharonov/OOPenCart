<?php

use yii\db\Migration;

/**
 * Class m180427_115832_add_column_access_to_extension
 */
class m180427_115832_add_column_access_to_extension extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('extension', 'access', $this->integer()->comment('Доступность в зависимости от авторизации'));
        $this->createIndex('idx-access-extension', 'extension', 'access');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('extension', 'access');
        $this->dropIndex('idx-access-extension', 'extension');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180427_115832_add_column_access_to_extension cannot be reverted.\n";

        return false;
    }
    */
}
