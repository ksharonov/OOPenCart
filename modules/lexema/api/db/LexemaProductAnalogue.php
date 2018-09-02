<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\ProductAnalogue;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\ProductRepository;

class LexemaProductAnalogue extends ProductAnalogue
{
	use Mapper;

	public $label = 'Аналог';
	public $labelAttribute = 'analogueTitle';

	/** @var string */
	public $analogueTitle;

	/** @var LexemaProduct */
	public $_parentProduct;

	/** @var LexemaProduct */
	public $_analogueProduct;

	public $map = [
		'ProductglobalId' => 'setAnalogue',
		'parentProductglobalId' => 'setParentProduct',
		'matcode' => null,
		'matname' => null,
	];

	public function rules()
	{
		return [
			[['productId', 'productAnalogueId'], 'required'],
			[['productId', 'productAnalogueId'], 'unique', 'targetAttribute' => ['productId', 'productAnalogueId']],
		];
	}

	public function setAnalogue($key, $value)
	{
		if (!$value) {
			return;
		}

		if (is_object($value)) {
			$this->productAnalogueId = $value->id;
			$this->analogueTitle = $value->title;
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
			$this->productAnalogueId = $dbProduct->id;
			$this->analogueTitle = $dbProduct->title;
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