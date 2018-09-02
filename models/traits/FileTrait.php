<?php

namespace app\models\traits;

use app\models\db\File;
use yii\db\ActiveQuery;

/**
 * Class FileTrait
 *
 * @property int $relModel
 * @property File $image
 * @property File $banner
 * @property File $mainImage
 * @property File[] $files
 * @property File[] $documents
 * @property File[] $images
 * @property File[] $banners
 * @method ActiveQuery hasMany($class, array $link)
 * @method ActiveQuery hasOne($class, array $link)
 */
trait FileTrait
{
    /**
     * Возвращает главное изображение
     *
     * @return ActiveQuery|null|\stdClass|mixed
     */
    public function getMainImage()
    {
        $emptyImage = new \stdClass();
        $emptyImage->path = '/images/emptyProduct.jpg';

        return $this->image ?? $emptyImage ?? null;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->hasOne(File::className(), ['relModelId' => 'id'])
            ->where([
                'type' => File::TYPE_IMAGE,
                'relModel' => $this->relModel
            ])
            ->orderBy('id ASC');
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->hasOne(File::className(), ['relModelId' => 'id'])
            ->where([
                'type' => File::TYPE_BANNER,
                'relModel' => $this->relModel
            ])
            ->orderBy('id ASC');
    }


    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => $this->relModel
            ]);
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => $this->relModel
            ])
            ->andWhere(['=', 'type', File::TYPE_DOCUMENT]);
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => $this->relModel
            ])
            ->andWhere(['=', 'type', File::TYPE_IMAGE]);
    }

    /**
     * @return mixed
     */
    public function getBanners()
    {
        return $this->hasMany(File::className(), ['relModelId' => 'id'])
            ->where([
                'relModel' => $this->relModel
            ])
            ->andWhere(['=', 'type', File::TYPE_BANNER]);
    }

	/**
	 * Возвращает сертификаты
	 *
	 * @return ActiveQuery
	 */
	public function getCertificates()
	{
		return $this->hasMany(File::className(), ['relModelId' => 'id'])
			->where([
				'relModel' => $this->relModel
			])
			->andWhere(['=', 'type', File::TYPE_CERTIFICATE]);
	}
}