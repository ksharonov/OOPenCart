<?php

namespace app\models\db;

use app\helpers\ModelRelationHelper;
use app\models\traits\FileTrait;
use app\system\base\Model;
use Yii;

/**
 * This is the model class for table "Storage".
 *
 * Модель таблицы магазинов и складов
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $status
 * @property string $timeBeginWork
 * @property string $timeEndWork
 * @property Address $address
 * @property Storage $parent
 * @property Storage[] $childs
 * @property City $city
 * @property array $fullAddress
 * @static array $types
 * @static array $statuses
 */
class Storage extends \app\system\db\ActiveRecord
{
    use FileTrait;

    const TYPE_STORAGE = 0;
    const TYPE_SHOP = 1;

    const STATUS_ACTIVE = 0;
    const STATUS_NOT_ACTIVE = 1;

    public static $types = [
        self::TYPE_STORAGE => 'Склад',
        self::TYPE_SHOP => 'Магазин'
    ];

    public static $statuses = [
        self::STATUS_ACTIVE => 'Активен',
        self::STATUS_NOT_ACTIVE => 'Неактивен'
    ];

    /**
     * Остаток по всем складам (из sql)
     * @var
     */
    public $_quantity;

    /*
     * Для конвертации в JSON
     */
    public $fullAddress;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['type', 'status', 'isMain', 'cityId', 'parentId'], 'integer'],
            [['timeBeginWork', 'timeEndWork'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['cityId'], 'required']
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
            'content' => 'Содержимое',
            'type' => 'Тип',
            'status' => 'Статус',
            'timeBeginWork' => 'Время начала работы',
            'timeEndWork' => 'Время завершения работы',
            'isMain' => 'Основной',
            'cityId' => 'Город'
        ];
    }

    /**
     * Остатки склада
     * @return \yii\db\ActiveQuery
     */
    public function getBalances()
    {
        return $this->hasMany(StorageBalance::className(), [
            'storageId' => 'id'
        ]);
    }

    /** Возвращает AR адреса
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['relModelId' => 'id'])
            ->where(['relModel' => ModelRelationHelper::REL_MODEL_STORAGE])
            ->orderBy('id');
    }

    /**
     * Текущее время
     * @return bool|\DateTime
     */
    public function getTime()
    {
        date_default_timezone_set('UTC');
        $defaultTimeZone = Setting::get('DEFAULT.TIME.ZONE') ?? 5;
        $date = date('H:i:s');
        $time = \DateTime::createFromFormat('H:i:s', $date);
        $time->modify("+{$defaultTimeZone} hours");
        return $time;
    }

    /**
     * Время начала работы
     * @return bool|\DateTime
     */
    public function getBeginTime()
    {
        date_default_timezone_set('UTC');
        $time = \DateTime::createFromFormat('H:i:s', $this->timeBeginWork);
        return $time;
    }

    /**
     * Время окончания работы
     * @return bool|\DateTime
     */
    public function getEndTime()
    {
        date_default_timezone_set('UTC');
        $time = \DateTime::createFromFormat('H:i:s', $this->timeEndWork);
        return $time;
    }

    /**
     * Работает ли сейчас склад/магазин
     * @return bool
     */
    public function isWork()
    {
        return $this->beginTime < $this->time && $this->time < $this->endTime;
    }

    /**
     * Работает ли склад/магазин сегодня
     * @return bool
     */
    public function isWorkTomorrow()
    {
        return $this->endTime > $this->time;
    }

    /**
     * Возможность забрать заказ сегодня
     * @return bool
     */
    public function isAvailableOrderTomorrow()
    {
        $orderProcessHours = Setting::get('ORDER.PROCESS.HOURS') ?? 4;
        $time = ($this->time)->add(new \DateInterval("PT{$orderProcessHours}H"));
        return $this->endTime > $time;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, [
            'id' => 'cityId'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Storage::className(), [
            'id' => 'parentId'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(Storage::className(), [
            'parentId' => 'id'
        ]);
    }
}
