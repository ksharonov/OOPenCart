<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "block".
 *
 * @property integer $id
 * @property string $title
 * @property string $blockKey
 * @property string $blockValue
 * @property integer $type
 * @property string $dtUpdate
 * @property string $dtCreate
 * @property array $views
 * @static array $types
 * @static Block $instance
 */
class Block extends \app\system\db\ActiveRecord
{

    const TYPE_NUM = 0;
    const TYPE_STRING = 1;
    const TYPE_HTML = 2;
    const TYPE_RAW = 3;
    const TYPE_DROPDOWN = 4;

    public static $types = [
        self::TYPE_NUM => 'Число',
        self::TYPE_STRING => 'Строка',
        self::TYPE_HTML => 'Html-блок',
        self::TYPE_RAW => 'Raw-строка',
        self::TYPE_DROPDOWN => 'Выпадающий блок с описанием'
    ];

    /** @var Block */
    private static $instance;

    public $views;

    /**
     * Параметры
     * @var
     */
    public $params;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'block';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {

        if ($this->type == self::TYPE_NUM) {
            $result = preg_replace("/[^ ,.0-9]/", '', $this->blockValue);
            $this->blockValue = $result;
        } elseif ($this->type == self::TYPE_STRING) {
            $result = htmlspecialchars($this->blockValue);
            $this->blockValue = $result;
        } elseif ($this->type == self::TYPE_HTML || $this->type == self::TYPE_DROPDOWN) {
            $this->blockValue = HtmlPurifier::process($this->blockValue);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function __construct($params = [])
    {
        $views = Block::find()->asArray()->all();

        foreach ($views as $v) {
            $this->views[$v['blockKey']] = $v['blockValue'];
            $this->params[$v['blockKey']] = (object)$v;
        }

        parent::__construct($params);
    }

    /**
     * Возвращает экземпляр этого класса
     *
     * @return Block
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
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
                'value' => new Expression('NOW()')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'blockKey', 'title'], 'required'],
            [['blockValue'], 'string'],
            [['type'], 'integer'],
            [['dtUpdate', 'dtCreate'], 'safe'],
            [['title', 'blockKey'], 'string', 'max' => 128],
            [['blockKey'], 'unique'],
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
            'blockKey' => 'Ключ',
            'blockValue' => 'Значение',
            'type' => 'Тип данных',
            'dtUpdate' => 'Дата обновления',
            'dtCreate' => 'Дата созадния',
        ];
    }

    /**
     * Возвращает содержимое блока
     *
     * @return string
     */
    public static function getView($key)
    {
        $instance = self::getInstance();
        $views = $instance->views;
        $result = isset($views[$key]) ? $views[$key] : null;
        return $result;
        //return self::findOne(['blockKey' => $key])->blockValue ?? null;
    }

    /**
     * todo Временная реализация
     * @param $key
     * @param string $mark
     * @param string $content
     * @return mixed
     */
    public static function getDropdown($key, $mark = '(?)', $content = null)
    {
        $instance = self::getInstance();
        $views = $instance->views;
        $result = $content ?? (isset($views[$key]) ? $views[$key] : null);

        $content = "<span data-dropdown-container><span data-dropdown-trigger>$mark</span><div data-dropdown-content>$result</div></span>";
        return $content;
    }
}
