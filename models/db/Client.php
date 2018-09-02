<?php

namespace app\models\db;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveQuery;
use app\models\db\Contract;
use app\models\db\User;
use yii\helpers\Json;
use app\helpers\ModelRelationHelper;
use app\models\db\File;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $title
 * @property integer $type
 * @property integer $status
 * @property integer $phone
 * @property string $dtUpdate
 * @property string $params
 * @property string $email
 * @property \stdClass $param
 * @property string $dtCreate
 * @property array $types
 * @property array $statuses
 * @property User[] $users
 * @property Contract $actualContract
 * @property Contract $lastContract
 * @property Contract[] $contracts
 * @property ProductPriceGroup $priceGroup
 * @static array $statuses
 * @static array $types
 */
class Client extends \app\system\db\ActiveRecord
{
    const TYPE_INDIVIDUAL = 0;
    const TYPE_ENTITY = 1;

    const STATUS_ACTIVE = 0;
    const STATUS_NOT_ACTIVE = 1;

    /** @var null|\stdClass */
//    public $param = null;

    /** @var array */
    public $paramsDefault = [
        'inn' => '',
        'kpp' => ''
    ];

    /** @var array */
    public static $types = [
        self::TYPE_INDIVIDUAL => 'Физическое лицо',
        self::TYPE_ENTITY => 'Юридическое лицо'
    ];

    public static $statuses = [
        self::STATUS_ACTIVE => 'Активен',
        self::STATUS_NOT_ACTIVE => 'Неактивен'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

//    public function __get($name)
//    {
//        if (!property_exists($this, $name) && isset($this->param->$name)) {
//            return $this->param->$name;
//        }
//
//        return parent::__get($name);
//    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
//        if (!$this->params) {
//            $this->params = $this->paramsDefault;
//        }
//
//        if (is_string($this->params)) {
//            $this->params = Json::decode($this->params);
//        }
//
//        $this->param = new \stdClass();
//        $this->params = $this->params ?? $this->paramsDefault;
//        $params = $this->params ?? [];
//
//        foreach ($params as $key => $value) {
//            $this->param->$key = $value;
//        }


        parent::afterFind();
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
        ];
    }

    /*
     * Возвращает доступную минимальную информацию о клиенте
     * @return array
     */
    public function getData()
    {
        return [
            'title' => $this->title,
            'phone' => $this->phone,
            'email' => $this->email
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
//        if (is_string($this->params)) {
//            $this->params = Json::decode($this->params);
//        }
//
//        $this->params = Json::encode($this->params);

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['dtUpdate', 'dtCreate'], 'safe'],
            [['title', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 32],
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
            'type' => 'Тип',
            'status' => 'Статус',
            'dtUpdate' => 'Дата обновления',
            'dtCreate' => 'Дата создания',
            'phone' => 'Телефон'
        ];
    }

    /**
     * Возвращает заказы пользователя
     *
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), [
            'clientId' => 'id'
        ]);
    }

    /**
     * Возвращает договора клиента
     *
     * @return ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), [
            'clientId' => 'id'
        ]);
    }

    /**
     * Возвращает контракт клиента
     *
     * @return Contract
     */
    public function getContract()
    {
        return $this->actualContract ?? $this->lastContract;
    }

    /**
     * Актуальный контракт
     *
     * @return ActiveQuery
     */
    public function getActualContract()
    {
        return $this->hasOne(Contract::className(), [
            'clientId' => 'id'
        ])
            ->where([
                'status' => Contract::STATUS_ACTIVE
            ])
            ->orderBy('id DESC');
    }

    /**
     * Последний контракт
     *
     * @return ActiveQuery
     */
    public function getLastContract()
    {
        return $this->hasOne(Contract::className(), [
            'clientId' => 'id'
        ])->orderBy('id DESC');
    }

    /**
     * Возвращает пользователей клиента
     *
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])
            ->viaTable('user_to_client', ['clientId' => 'id'])
            ->with('userToClient');
    }

    /**
     * Возвращает объект таблицы связей Клиента-Пользователя
     *
     * @return ActiveQuery
     */
    public function getUserToClient()
    {
        return $this->hasMany(UserToClient::className(), ['clientId' => 'id']);
    }

    /**
     * Возвращает файлы
     *
     * @return ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => ModelRelationHelper::REL_MODEL_CLIENT
            ]);
    }

    /**
     * Возвращает прайсы цен
     *
     * @return ActiveQuery
     */
    public function getPriceGroup()
    {
        return $this->hasOne(ProductPriceGroup::className(), [
            'id' => 'productPriceGroupId'
        ])
            ->viaTable('product_price_group_to_client', ['clientId' => 'id']);
    }

}
