<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;
use app\models\db\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "product_review".
 *
 * @property integer $id
 * @property string $title
 * @property string $comment
 * @property string $positive
 * @property string $negative
 * @property integer $productId
 * @property integer $userId
 * @property integer $rating
 * @property string $dtCreate
 * @property string $dtUpdate
 * @property string $author
 * @property integer $status
 * @property User $user
 * @property Product $product
 */
class ProductReview extends \app\system\db\ActiveRecord
{

    /**
     * Статусы записи
     */
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * Массив статусов
     * @var array
     */
    public static $reviews = [
        self::STATUS_NOT_ACTIVE => 'Неактивен',
        self::STATUS_ACTIVE => 'Активен'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment', 'positive', 'negative'], 'string'],
            [['productId', 'userId', 'rating', 'status'], 'integer'],
            [['dtCreate', 'dtUpdate'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'comment' => 'Комментарий',
            'positive' => 'Позитивное',
            'negative' => 'Негативное',
            'productId' => 'Продукт',
            'userId' => 'Пользователь',
            'rating' => 'Рейтинг',
            'dtCreate' => 'Дата создания',
            'dtUpdate' => 'Дата обновления',
            'status' => 'Статус'
        ];
    }

    /**
     * Возвращает продукт
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'productId']);
    }

    /**
     * Возвращает пользователя
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * Возвращает имя автора
     * @return string
     */
    public function getAuthor()
    {
        /** @var User $user */
        $user = $this->user;

        if ($user && isset($user->username)) {
            return $user->username;
        } else {
            return "Неизвестный пользователь";
        }
    }
}
