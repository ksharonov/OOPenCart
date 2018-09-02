<?php

use yii\db\Migration;

/**
 * Handles the creation of table `session`.
 */
class m180709_125601_create_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('session', [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer()
        ]);

        $this->addPrimaryKey('session_pk', 'session', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('session');
    }
}
