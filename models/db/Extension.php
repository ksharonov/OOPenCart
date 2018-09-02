<?php

namespace app\models\db;

use Yii;
use app\system\extension\DeliveryExtension;
use app\system\extension\PaymentExtension;

/**
 * This is the model class for table "extension".
 *
 * @property integer $id
 * @property string $title
 * @property string $class
 * @property integer $type
 * @property integer $status
 * @property string $name
 * @property string $params
 * @property integer $parentId
 * @property Extension[] $childs
 * @property Extension $child
 * @property integer $access
 * @static array $types
 * @static array $statuses
 */
class Extension extends \app\system\db\ActiveRecord
{

    const EXTENSION_TYPE_PAYMENT = 0;
    const EXTENSION_TYPE_DELIVERY = 1;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const ACCESS_GUEST = 0;
    const ACCESS_AUTH = 1;
    const ACCESS_ENTITY = 2;

    /** @var array Тип расширения */
    public static $types = [
        self::EXTENSION_TYPE_PAYMENT => 'Способы оплаты',
        self::EXTENSION_TYPE_DELIVERY => 'Способы доставки'
    ];

    /** @var array класс расширения */
    public static $extensionClasses = [

    ];

    /** @var array Статусы расширений */
    public static $statuses = [
        self::STATUS_NOT_ACTIVE => 'Неактивен',
        self::STATUS_ACTIVE => 'Активен'
    ];

    /** @var array Доступность */
    public static $accesses = [
        self::ACCESS_GUEST => 'Для гостей',
        self::ACCESS_AUTH => 'Для авторизованных'
    ];

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        self::$extensionClasses[self::EXTENSION_TYPE_PAYMENT] = PaymentExtension::className();
        self::$extensionClasses[self::EXTENSION_TYPE_DELIVERY] = DeliveryExtension::className();

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extension';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'parentId', 'access'], 'integer'],
            [['title', 'name'], 'string', 'max' => 128],
            [['class'], 'string', 'max' => 256],
            [['image'], 'string', 'max' => 512],
            [['params'], 'string']
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
            'name' => 'Имя',
            'class' => 'Класс',
            'type' => 'Тип',
            'status' => 'Статус',
            'params' => 'Параметры виджета',
            'image' => 'Логотип',
            'parentId' => 'Родительское расширение',
            'access' => 'Доступность'
        ];
    }

    /**
     * Получение класса виджета
     *
     * @param int $id
     * @return string
     */
    public static function get($id)
    {
        return self::findOne($id)->class ?? null;
    }

    /**
     * Получение класса виджета по имени
     *
     * @param string $name
     * @return string
     */
    public static function getByName(string $name)
    {
        return self::findOne(['name' => $name])->class ?? null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasOne(self::class, ['parentId' => 'id'])
            ->where(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(self::class, ['parentId' => 'id'])
            ->where(['status' => self::STATUS_ACTIVE]);
    }
}
