<?php

use yii\db\Migration;

/**
 * Handles the creation of table `outer_rel`.
 */
class m171227_054955_create_outer_rel_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('outer_rel', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->comment('Внешний идентификатор'),
            'relModel' => $this->integer()->comment('Связанная модель'),
            'relModelId' => $this->integer()->comment('id связанной модели')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('outer_rel');
    }
}
