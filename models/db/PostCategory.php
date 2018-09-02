<?php

namespace app\models\db;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property Post[] $posts
 */
class PostCategory extends \app\system\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug'], 'required'],
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
            'slug' => 'Слаг',
        ];
    }

    /**
     * Возвращает посты этой категории
     *
     * @return ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['categoryId' => 'id']);
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // Проверка на уникальность слага и замена его на номерной
        if(isset($changedAttributes['slug']) || $insert) {
            $oldSlug = $this->slug;

            $newSlug = $oldSlug;
            while ($this::countBySlug($newSlug) > 1) {
                $newSlug = $oldSlug;
                $newSlug .= '-new';
            }
            
            $this->slug = $newSlug;
            $this->save();
        }
    }


    /**
     * Возвращает количество постов по слагу
     *
     * @param $slug
     * @return int|string
     */
    private static function countBySlug($slug)
    {
        return PostCategory::find()->where(['slug' => $slug])->count();
    }
}
