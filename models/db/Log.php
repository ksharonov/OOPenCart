<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property string $content
 * @property string $dtCreate
 * @property string $dtUpdate
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['dtCreate', 'dtUpdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'dtCreate' => 'Dt Create',
            'dtUpdate' => 'Dt Update',
        ];
    }

    public static function toLog($content)
    {
        $log = new self();
        $log->content = $content;
        $log->save();
    }
}
