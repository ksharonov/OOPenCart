<?php

namespace app\models\db;

use app\system\behaviors\TextJsonColumnsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "cheque".
 *
 * @property integer $id
 * @property string $requestId
 * @property string $url
 * @property integer $success
 * @property string $params
 * @property string $dtCreate
 * @property string $dtUpdate
 * @property string $path
 * @property string $responseContent
 * @property integer $orderId
 * @property integer $fd
 * @property integer $fn
 */
class Cheque extends \app\system\db\ActiveRecord
{
    /**
     * Безуспешная отправка
     */
    const IS_NOT_SUCCESS = 0;

    /**
     * Успешная отправка
     */
    const IS_SUCCESS = 1;

    /**
     * @var array
     */
    public static $successStatuses = [
        self::IS_NOT_SUCCESS => 'Неотправлено',
        self::IS_SUCCESS => 'Отправлено'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cheque';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['success', 'orderId'], 'integer'],
            [['params', 'responseContent'], 'string'],
            [['dtCreate', 'dtUpdate'], 'safe'],
            [['requestId'], 'string', 'max' => 32],
            [['url'], 'string', 'max' => 256],
            [['path'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dtCreate',
                'updatedAtAttribute' => 'dtUpdate',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => TextJsonColumnsBehavior::className(),
                'attributes' => ['params', 'responseContent']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'requestId' => 'id-запроса',
            'url' => 'Путь запроса',
            'success' => 'Статус отправки',
            'params' => 'Содержимое',
            'dtCreate' => 'Дата создания',
            'dtUpdate' => 'Дата обновления',
            'path' => 'Путь',
            'orderId' => 'Заказ'
        ];
    }

    /**
     * Получить номер ФД
     * @return integer
     */
    public function getFd()
    {
        return ((object)$this->responseContent)->FiscalDocNumber ?? null;
    }

    /**
     * Получить номер ФН
     * @return integer
     */
    public function getFn()
    {
        return ((object)$this->responseContent)->FiscalSign ?? null;
    }
}
