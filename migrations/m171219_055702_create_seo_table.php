<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo`.
 */
class m171219_055702_create_seo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('seo', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->comment('Заголовок'),
            'meta_keywords' => $this->string()->comment('Ключевые слова'),
            'meta_description' => $this->string()->comment('Описание'),
            'params' => 'json DEFAULT NULL',
            'relModel' => $this->integer()->comment('Связанная модель'),
            'relModelId' => $this->integer()->comment('id связанной модели')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('seo');
    }
}
