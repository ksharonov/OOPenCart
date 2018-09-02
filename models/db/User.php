<?php

namespace app\models\db;

use app\models\traits\LexemaUserTrait;
use app\models\traits\OneCUserTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Json;
use app\models\db\Client;
use app\models\db\UserProfile;
use app\models\db\Order;

/**
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $dtCreate
 * @property string $accessToken
 * @property string $authKey
 * @property string $email
 * @property string $changePasswordKey
 * @property string $params
 * @property array $data
 * @property \stdClass $param
 * @property string $fio
 * @property string $shortFio
 * @property integer $phone
 * @property integer $type
 * @property integer $status
 * @property integer $clientType
 * @property Order[] $orders
 * @property UserProfile $profile
 * @property ProductReview[] $reviews
 * @property UserToClient[] $userToClient
 * @property Client[] $clients
 * @property Client $client
 * @property Client $defaultClient
 * @property User $manager
 * @static array $types
 */
class User extends \app\system\db\ActiveRecord implements \yii\web\IdentityInterface
{
    use LexemaUserTrait;
    use OneCUserTrait;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    /** @var array массив поля params */
    public $paramsArray = [];

    /** @var bool */
    public $fromRemote = false;

    public static $statuses = [
        self::STATUS_NOT_ACTIVE => 'Не активирован',
        self::STATUS_ACTIVE => 'Активирован',
        self::STATUS_DELETED => 'Удалён'
    ];

    public $param = null;
    public $clientType;

    /**
     * @var string повторный пароль
     */
    public $repeatPassword;

    /**
     * @var
     */
    public $_client;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->params = Json::encode($this->paramsArray);

        if ($this->isNewRecord) {
            $this->status = self::STATUS_ACTIVE;
        }

        if ($this->isNewRecord || ($this->isAttributeChanged('password') && !empty($this->password) && $this->password == $this->repeatPassword)) {
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
        }

        if (empty($this->password) || empty($this->repeatPassword)) {
            $this->password = $this->oldAttributes['password'];
        }

        if (!Yii::$app->request->isConsoleRequest) {
            $session = Yii::$app->session;
            $phoneChanged = $session->get('phoneChanged', false);

            if (!$phoneChanged) {
                $this->phone = $this->oldAttributes['phone'] ?? null;
            }

        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeDelete()
    {
        return parent::beforeDelete();
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $cookies = [];

        if (!$this->params) {
            $this->params = [];
            $this->paramsArray = Json::encode([]);
        }

        if (is_string($this->params)) {
            $this->paramsArray = Json::decode($this->params);
        }

        if (isset(Yii::$app->request->cookies)) {
            $cookies = Yii::$app->request->cookies;
        }

        $profile = $this->profile;


        if (!$this->fromRemote && !Yii::$app->request->isConsoleRequest) {
            //todo вынесу чуть позже это в компоненты cart/compare/favorite (Кирилл Ш., 19.03.18)
            if (!$cookies['cart'] && isset($cookies['cart']->value)) {
                $cookies['cart']->value = $profile->cartData;
            }

            if (!$cookies['compare'] && isset($cookies['compare']->value)) {
                $cookies['compare']->value = $profile->compareData;
            }

            if (!$cookies['favorite'] && isset($cookies['favorite']->value)) {
                $cookies['favorite']->value = $profile->favoriteData;
            }
        }
        $this->createClientAndProfile();
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $isNewRecord = array_key_exists('id', $changedAttributes) && !$changedAttributes['id'];

        if ($isNewRecord && !$this->fromRemote) {
            $this->createClientAndProfile();
            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole('user');
            $auth->assign($userRole, $this->id);
        }

        //todo поправить сохранение params/paramsArray
        $this->paramsArray = Json::decode($this->params);
        parent::afterSave($insert, $changedAttributes);
    }

    public function createClientAndProfile()
    {
        if (!$this->clients) {
            $client = new Client();
            $client->title = $this->fio;
            $client->phone = $this->phone;
            $client->type = $this->clientType;
            $client->save();
            UserToClient::bind($this, $client);
        }

        if (!$this->profile) {
            $profile = new UserProfile();
            $profile->userId = $this->id;
            $profile->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $this->param = new \stdClass();
        $params = Json::decode($this->params) ?? [];

        foreach ($params as $key => $value) {
            $this->param->$key = $value;
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'clientType', 'type'], 'integer'],
            [['dtCreate'], 'safe'],
            [['username'], 'string', 'max' => 40],
            [['password', 'repeatPassword'], 'string', 'max' => 255],
            [['accessToken', 'authKey'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 255],
            [['fio'], 'string', 'max' => 255],
            [['changePasswordKey'], 'string', 'max' => 32],
            [['accessToken'], 'unique'],
            [['params'], 'string'],
            [['phone'], 'string', 'max' => 32],
            [['email', 'username'], 'unique', 'on' => ['register']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'username' => 'Логин',
            'password' => 'Пароль',
            'fio' => 'ФИО',
            'type' => 'Тип пользователя',
            'phone' => 'Телефон',
            'clientType' => 'Тип клиента',
            'status' => 'Статус пользователя',
            'email' => 'Почтовый адрес',
            'repeatPassword' => 'Повторить пароль'
        ];
    }

    /**
     * Возвращает ФИО пользователя
     *
     * @return string
     */
    public function getName()
    {
        return $this->fio != "" ? $this->fio : $this->username;
    }

    /**
     * Генерация токена
     *
     * @return void
     */
    public function generateToken()
    {
        $this->accessToken = $this->getAuthKey();
    }

    /**
     * Возвращает пользователя по id
     *
     * @param $id
     * @return User
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Возвращает id пользователя
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /*
     * Возвращает доступную минимальную информацию о пользователе
     * @return array
     */
    public function getData()
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'fio' => $this->fio,
            'phone' => $this->phone
        ];
    }

    /**
     * Возвращает пользователя по имени
     *
     * @param $username
     * @return User
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username' => $username])->one();
    }

    /**
     * Возвращает пользователя по mail
     *
     * @param $email
     * @deprecated
     * @return User
     */
    public static function findByUseremail($email)
    {
        return self::findOne(['email' => $email]);
    }

    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getAuthKey()
    {
        return \Yii::$app->security->generateRandomString();
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Возвращает заказы пользователя
     *
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['userId' => 'id']);
    }

    /**
     * Возвращает клиентов пользователя
     *
     * @return ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(Client::className(), ['id' => 'clientId'])
            ->viaTable('user_to_client', ['userId' => 'id']);
    }

    /**
     * Возвращает выбранного клиента
     * todo позже изменить данный метод
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->defaultClient;

        /** @var integer $selectedClientId в будущем выбранный пользователем клиент */
//        $selectedClientId = 1;
//        return Client::findOne($selectedClientId);
    }

    public function setClient(Client $client)
    {
        $this->_client = $client;
    }

    /**
     * Возвращает первичного, созданного при регистрации, клиента пользователя
     *
     * @return Client
     */
    public function getDefaultClient()
    {
        return Client::find()
            ->joinWith('userToClient')
            ->where([
                'user_to_client.userId' => $this->id,
                'user_to_client.position' => UserToClient::POS_DEFAULT
            ])
            ->orWhere([
                'user_to_client.userId' => $this->id,
                'user_to_client.position' => UserToClient::POS_DIRECTOR
            ])
            ->one();
    }

    /**
     * Возвращает объект таблицы связей Клиента-Пользователя
     *
     * @return ActiveQuery
     */
    public function getUserToClient()
    {
        return $this->hasMany(UserToClient::className(), ['userId' => 'id']);
    }

    /**
     * Возвращает профиль пользователя
     *
     * @return ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['userId' => 'id']);
    }

    /**
     * Возвращает отзывы
     *
     * @return ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(ProductReview::className(), ['userId' => 'id']);
    }

    /**
     * Возвращает короткое ФИО-пользователя
     *
     * @return string
     */
    public function getShortFio(): string
    {
        return $this->fio;
    }

    /**
     * Синоним ФИО
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->fio;
    }

    /**
     * Возвращает менеджера пользователя
     * Пока возвращает самого себя
     * todo ссылаться на манагера
     *
     * @return User
     */
    public function getManager(): User
    {
        return $this;
    }
}