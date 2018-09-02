<?php

namespace app\models\db;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveQuery;
use app\helpers\ModelRelationHelper;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $dtUpdate
 * @property string $dtCreate
 */
class Page extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
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
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['dtUpdate', 'dtCreate'], 'safe'],
            [['title', 'slug'], 'string', 'max' => 255],
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
            'slug' => 'Название ссылки',
            'content' => 'Содержимое',
            'dtUpdate' => 'Дата обновления',
            'dtCreate' => 'Дата создания'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {

        Seo::deleteAll([
            'relModel' => ModelRelationHelper::REL_MODEL_PAGE,
            'relModelId' => $this->id
        ]);

        return parent::beforeDelete();
    }

    /**
     * Возвращает SEO-модель страницы
     *
     * @return ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PAGE
            ]);
    }
}
