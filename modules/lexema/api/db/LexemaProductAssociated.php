<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\ProductAssociated;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\ProductRepository;

class LexemaProductAssociated extends ProductAssociated
{
	use Mapper;

	public $label = 'Сопутствующий товар';
	public $labelAttribute = 'associatedTitle';

	/** @var string */
	public $associatedTitle;

	public $map = [
		'ProductglobalId' => 'setAssociated',
		'parentProductglobalId' => 'setParentProduct',
		'matcode' => null,
		'matname' => null,
	];

	public function rules()
	{
		return [
			[['productId', 'productAssociatedId'], 'required'],
			[['productId', 'productAssociatedId'], 'unique', 'targetAttribute' => ['productId', 'productAssociatedId']],
		];
	}

	public function setAssociated($key, $value)
	{
		if (!$value) {
			return;
		}

		if (is_object($value)) {
			$this->productAssociatedId = $value->id;
			$this->associatedTitle = $value->title;
			return;
		}

		$dbProduct = LexemaProduct::findByGuid($value);

		if (!$dbProduct) {
			$apiProduct = ProductRepository::get()
				->find(['globalId' => $value])
				->one();

			if ($apiProduct) {
				$dbProduct = new LexemaProduct($apiProduct);
				$dbProduct->save(false);
			}
		}

		if (isset($dbProduct->id)) {
			$this->productAssociatedId = $dbProduct->id;
			$this->associatedTitle = $dbProduct->title;
		}

	}

	public function setParentProduct($key, $value)
	{
		if (!$value) {
			return;
		}

		if (is_object($value)) {
			$this->productId = $value->id;
			return;
		}

		$dbProduct = LexemaProduct::findByGuid($value);

		if (!$dbProduct) {
			$apiProduct = ProductRepository::get()
				->find(['globalId' => $value])
				->one();

			if ($apiProduct) {
				$dbProduct = new LexemaProduct();
				$dbProduct->save(false);
			}
		}

		if (isset($dbProduct->id)) {
			$this->productId = $dbProduct->id;
		}
	}
}