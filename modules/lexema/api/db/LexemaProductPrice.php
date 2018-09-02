<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use app\models\db\City;
use app\models\db\ProductPrice;
use app\models\db\Storage;
use app\models\db\StorageBalance;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\PriceGroupRepository;
use app\modules\lexema\api\repository\ProductRepository;
use app\modules\lexema\api\repository\ShopRepository;
use app\modules\lexema\api\repository\StorageRepository;
use app\system\db\ActiveRecord;

/**
 * Class LexemaProductPrice
 * @package app\modules\lexema\api\db
 */
class LexemaProductPrice extends ProductPrice
{
	use Mapper;

	/** @var LexemaProduct */
	public static $currentProduct;

	public $label = 'Цена товара';
	public $labelAttribute = 'productTitle';

	public $productTitle;

	public $map = [
		'ProductGlobalId' => 'setProduct',
		'EdizmGlobalId' => null,
		'TypeZenaGlobalId' => 'setPriceGroup',
		'ozena2' => 'value',
	];

	public static function setCurrentProduct(LexemaProduct $product)
	{
		self::$currentProduct = $product;
	}

	public function setProduct($key, $value)
	{
		if (!$value) {
			return;
		}

		if (self::$currentProduct) {
			$this->productId = self::$currentProduct->id;
			$this->productTitle = self::$currentProduct->title;
			return;
		}

		$dbProduct = LexemaProduct::findByGuid($value);

		if (!$dbProduct) {
			$apiProduct = ProductRepository::get()
				->find(['globalId' => $value])
                                ->limit(1)
				->one();

			if ($apiProduct) {
				$dbProduct = new LexemaProduct($apiProduct);
				$dbProduct->save(false);
			}
		}

		if (isset($dbProduct->id)) {
			$this->productId = $dbProduct->id;
			$this->productTitle = $dbProduct->title;
		}
	}

	public function setPriceGroup($key, $value)
	{
		if (!$value) {
			return;
		}

		$dbPriceGroup = LexemaPriceGroup::findByGuid($value);

		if (!$dbPriceGroup) {
			$apiPriceGroup = PriceGroupRepository::get()
				->find(['GlobalId' => $value])
                                ->limit(1)
				->one();

			if ($apiPriceGroup) {
				$dbPriceGroup = new LexemaPriceGroup($apiPriceGroup);
				$dbPriceGroup->save(false);
			}
		}

		if (isset($dbPriceGroup->id)) {
			$this->productPriceGroupId = $dbPriceGroup->id;
		}
	}

//	public function afterSave( $insert, $changedAttributes )
//	{
//		parent::afterSave($insert, $changedAttributes);
//	}
}