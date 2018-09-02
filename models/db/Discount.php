<?php

namespace app\models\db;

use app\helpers\ModelRelationHelper;
use app\system\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "discount".
 *
 * @property integer $id
 * @property string $title
 * @property integer $relModel
 * @property integer $relModelId
 * @property integer $type
 * @property integer $status
 * @property integer $value
 * @property string $dtStart
 * @property string $dtEnd
 * @property integer $priority
 */
class Discount extends ActiveRecord
{
    /**
     * Статусы скидки
     */
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * Скидка для товара в рублях
     */
    const TYPE_VALUE = 0;

    /**
     * Скидка для товара в процентах
     */
    const TYPE_PERCENT = 1;

    /**
     * Скидка в зависимости от суммы корзины
     */
    const TYPE_CART_VALUE = 2;

    /**
     * Массив приоритетов
     * @var array
     */
    public static $priority = [
        ModelRelationHelper::REL_MODEL_PRODUCT => 100,
        ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY => 200,
        ModelRelationHelper::REL_MODEL_ORDER => 300
    ];

    /**
     * Статусы
     * @var array
     */
    public static $statuses = [
        self::STATUS_NOT_ACTIVE => 'Неактивна',
        self::STATUS_ACTIVE => 'Активен'
    ];

    /**
     * Типы
     * @var array
     */
    public static $types = [
        self::TYPE_VALUE => 'Скидка от цены товара',
//        self::TYPE_PERCENT => 'Процентная скидка'
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relModel', 'relModelId', 'type', 'status', 'value', 'priority'], 'integer'],
            [['dtStart', 'dtEnd'], 'safe'],
            [['title'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'relModel' => 'Связанная модель',
            'relModelId' => 'id-связанной модели',
            'type' => 'Тип скидки',
            'status' => 'Статус',
            'value' => 'Значение',
            'dtStart' => 'Дата начала',
            'dtEnd' => 'Дата окончания'
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->priority = self::$priority[$this->relModel];
        return parent::beforeSave($insert);
    }
}
