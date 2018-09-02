<?php

namespace app\models\db;

use app\helpers\ModelRelationHelper;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "manufacturer".
 *
 * @property integer $id
 * @property string $title
 * @property string $vendorCode
 * @property string $shortDescription
 * @property string $description
 * @property string $slug
 * @property string $link
 */
class Manufacturer extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['vendorCode'], 'string', 'max' => 64],
            [['shortDescription'], 'string', 'max' => 70],
            [['slug'], 'string', 'max' => 255],
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
            'vendorCode' => 'Vendor Code',
            'shortDescription' => 'Short Description',
            'description' => 'Описание',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(File::className(), ['relModelId' => 'id'])
                ->where(['relModel' => ModelRelationHelper::REL_MODEL_MANUFACTURER]) ?? null;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        $path = 'brands';

        return Yii::$app->urlManager->createAbsoluteUrl(["/$path/" . $this->slug . '/']);
    }

    /**
     *
     */
    public function getProducts()
    {
       return $this->hasMany(Product::class, ['manufacturerId' => 'id']);
    }
}
