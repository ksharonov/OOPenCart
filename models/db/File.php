<?php

namespace app\models\db;

use Yii;
use \yii\db\ActiveQuery;
use app\helpers\ModelRelationHelper;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property integer $relModelId
 * @property integer $relModel
 * @property string $path
 * @property string $link
 * @property integer $type
 * @property integer $status
 * @property Product $product
 * @property Client $client
 * @property User $user
 * @property string $title
 * @property string $goodPath
 * @static array $fileStatuses
 * @static array $fileTypes
 * @static array $relModels
 * @static array $uploadedFiles
 */
class File extends \app\system\db\ActiveRecord
{

    const FILE_HIDDEN = 0;
    const FILE_PUBLISHED = 1;

    const TYPE_DOCUMENT = 0;
    const TYPE_IMAGE = 1;
    const TYPE_TX = 2;
    const TYPE_CERTIFICATE = 3;
    const TYPE_BANNER = 4;

    public static $fileStatuses = [
        self::FILE_HIDDEN => 'Скрыт',
        self::FILE_PUBLISHED => 'Опубликован',
    ];

    public static $fileTypes = [
        self::TYPE_DOCUMENT => 'Документ',
        self::TYPE_IMAGE => 'Изображение',
        self::TYPE_TX => 'Технические характеристики',
        self::TYPE_CERTIFICATE => 'Сертификат',
        self::TYPE_BANNER => 'Баннер'
    ];

    public static $uploadedFiles = [
        ModelRelationHelper::REL_MODEL_USER => [

        ],
        ModelRelationHelper::REL_MODEL_CLIENT => [

        ],
        ModelRelationHelper::REL_MODEL_PRODUCT => [
            self::TYPE_DOCUMENT, self::TYPE_TX, self::TYPE_CERTIFICATE
        ],
        ModelRelationHelper::REL_MODEL_PRODUCT_CATEGORY => [
            self::TYPE_BANNER
        ],
        ModelRelationHelper::REL_MODEL_MANUFACTURER => [
            self::TYPE_BANNER
        ],
        ModelRelationHelper::REL_MODEL_STORAGE => [
            self::TYPE_BANNER
        ],
        ModelRelationHelper::REL_MODEL_POST => [
            self::TYPE_IMAGE
        ],
        ModelRelationHelper::REL_MODEL_PAGE => [
            self::TYPE_IMAGE
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'relModel', 'relModelId'], 'required'],
            [['type', 'status', 'relModel', 'relModelId', 'size'], 'integer'],
            [['path', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'relModel' => 'Ид модели',
            'relModelId' => 'Ид связанной модели',
            'path' => 'Путь до файла',
            'type' => 'Тип',
            'status' => 'Статус',
            'size' => 'Размер файла',
            'title' => 'Название файла'
        ];
    }

    /**
     * Возвращает типы файла, которые доступны для загрузки
     *
     * @return array
     */
    public static function getFileTypesByModelType($modelType = null)
    {
        $data = [];

        if (!isset(File::$uploadedFiles[$modelType])) {
            return [];
        }

        foreach (File::$uploadedFiles[$modelType] as $type) {
            $data[$type] = File::$fileTypes[$type];
        }
        return $data;
    }

    /**
     * Возвращает ссылку на файл
     *
     * @return string
     */
    public function getLink()
    {
        return Setting::get('SITE.URL') . $this->path;
    }

    /**
     * Возвращает пользователя, к которому прикреплен этот файл
     *
     * @return ActiveQuery | null
     */
    public function getUser()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_USER) {
            return $this->hasOne(User::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Возвращает клиента, к которому прикреплен этот файл
     *
     * @return ActiveQuery | null
     */
    public function getClient()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_CLIENT) {
            return $this->hasOne(Client::className(), [
                'id' => 'relModelId'
            ]);
        } else {
            return null;
        }
    }

    /**
     * Возвращает товар, к которому прикреплен этот файл
     *
     * @return ActiveQuery | null
     */
    public function getProduct()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PRODUCT) {
            return $this->hasOne(Product::className(), [
                'id' => 'relModelId'
            ]);
        } else {
            return null;
        }
    }

    /**
     * Возвращает заказ, к которому прикреплен этот файл
     *
     * @return ActiveQuery | null
     */
    public function getOrder()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_ORDER) {
            return $this->hasOne(Order::className(), [
                'id' => 'relModelId'
            ]);
        } else {
            return null;
        }
    }

    public function getGoodPath()
    {
        return trim($this->path);
    }
}
