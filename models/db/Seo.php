<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;
use app\models\db\Product;
use app\models\db\Post;
use app\models\db\Page;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use app\helpers\ModelRelationHelper;

/**
 * This is the model class for table "seo".
 *
 * @property integer $id
 * @property string $title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $params
 * @property Product $product
 * @property Post $post
 * @property Page $page
 * @property integer $relModel
 * @property integer $relModelId
 * @property \stdClass $param
 * @static array $relModels
 */
class Seo extends \app\system\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        if (!$this->params){
            $this->params = Json::encode([]);
        }
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['params'], 'string'],
            [['relModel', 'relModelId'], 'integer'],
            [['title', 'meta_keywords', 'meta_description'], 'string', 'max' => 255],
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
            'meta_keywords' => 'Ключевые слова',
            'meta_description' => 'Описание',
            'params' => 'Параметры',
            'relModel' => 'Связанная модель',
            'relModelId' => 'id связанной модели'
        ];
    }


    /**
     * Возвращает SEO для модели (продукта | поста | страницы)
     *
     * @return ActiveRecord | null
     */
    public static function findSeoByModel(ActiveRecord $model)
    {
        $modelSeo = self::find();

        if ($model instanceof Product) {
            $modelSeo->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT
            ]);
        } elseif ($model instanceof Post) {
            $modelSeo->where([
                'relModel' => ModelRelationHelper::REL_MODEL_POST
            ]);
        } elseif ($model instanceof Page) {
            $modelSeo->where([
                'relModel' => ModelRelationHelper::REL_MODEL_PAGE
            ]);
        } else {
            return null;
        }

        $modelSeo->andWhere([
            'relModelId' => $model->id
        ]);

        return $modelSeo->one() ?? null;
    }

    /**
     * Возвращает продукт
     *
     * @return ActiveQuery | null
     */
    public function getProduct()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PRODUCT) {
            return $this->hasOne(Product::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Возвращает пост
     *
     * @return ActiveQuery | null
     */
    public function getPost()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_POST) {
            return $this->hasOne(Post::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }

    /**
     * Возвращает страницу
     *
     * @return ActiveQuery | null
     */
    public function getPage()
    {
        if ($this->relModel == ModelRelationHelper::REL_MODEL_PAGE) {
            return $this->hasOne(Page::className(), ['id' => 'relModelId']);
        } else {
            return null;
        }
    }
}
