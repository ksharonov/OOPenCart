<?php

use yii\db\Migration;

/**
 * Handles the creation of table `templates`.
 */
class m180119_095926_create_templates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('template', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->comment('Название шаблона'),
            'class' => $this->string(256)->comment('Класс шаблона'),
            'params' => $this->text()->comment('Параметры шаблона')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('template');
    }
}
