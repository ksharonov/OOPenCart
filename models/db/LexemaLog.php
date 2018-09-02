<?php

namespace app\models\db;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "lexema_log".
 *
 * @property int $id
 * @property int $type Вид операции
 * @property string $dtCreate Дата создания
 * @property string $dtUpdate Дата обновления
 * @property int $count Количество загруженных объектов
 * @property string $params Техническая информация
 */
class LexemaLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lexema_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'count'], 'integer'],
            [['dtCreate', 'dtUpdate'], 'safe'],
            [['params'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'type'     => 'Type',
            'dtCreate' => 'Dt Create',
            'dtUpdate' => 'Dt Update',
            'count'    => 'Count',
            'params'   => 'Params',
        ];
    }

    public static function toLog($type, $count, $params)
    {
        $lexemaLog = new self();
        $lexemaLog->type = $type;
        $lexemaLog->count = $count;

        if (is_array($params)){
            $lexemaLog->params = Json::encode($params);
        } elseif(is_string($params)){
            $lexemaLog->params = $params;
        }
        $lexemaLog->save(falses);
    }
}
