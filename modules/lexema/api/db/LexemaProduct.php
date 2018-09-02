<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\helpers\LexemaHelper;
use app\helpers\StringHelper;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\ProductToCategory;
use app\models\db\ProductUnit;
use app\models\db\Setting;
use app\models\db\Unit;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\AnalogueRepository;
use app\modules\lexema\api\repository\AssociatedRepository;
use app\modules\lexema\api\repository\ManufacturerRepository;
use app\modules\lexema\api\repository\PriceGroupRepository;
use app\modules\lexema\api\repository\PriceRepository;
use app\modules\lexema\api\repository\ProductFileRepository;
use app\modules\lexema\api\repository\ProductRepository;
use app\modules\lexema\api\repository\SalesRepository;
use app\modules\lexema\api\repository\StorageBalanceRepository;
use app\modules\lexema\api\repository\StorageRepository;
use app\modules\lexema\api\repository\UnitRepository;
use yii\helpers\Json;

/**
 * Class LexemaProduct
 * @package app\modules\lexema\api\db
 */
class LexemaProduct extends Product
{
	use Mapper;

	/** @var ProductCategory */
	static $newCategory;

	/** @var ProductCategory */
	static $salesCategory;

	public $label = 'Товар';
	public $labelAttribute = 'title';

	/** @var Unit */
	public $_unit;

	/** @var ProductCategory */
	public $_category;

	/** @var integer */
	public $_new;

	/** @var LexemaProduct */
	public $_childProduct;

	/** @var LexemaProduct */
	public $_parentProduct;

	/** @var \stdClass */
	public $apiParams;

	public $sqlPrices;

	protected $saved;

	public $map = [
		'globalId' => 'guid',
		'parentglobalId' => 'setDbCategory',
		'name' => 'settitle',
		'note' => 'setDbContent',
		'artikul' => 'vendorCode',
		'length' => 'setDbAttributes',
		'height' => 'setDbAttributes',
		'width' => 'setDbAttributes',
		'weight'=> 'setDbAttributes',
		'volume' => 'setDbAttributes',
		'vcode' => 'backCode',
		'pruduceglogalid' => 'setDbManufacturer',
		'EdizmglobalId' => 'setunit',
		'IsNewProduct' => '_new',
	];

	public function rules()
	{
		return [
			[['backCode'], 'unique'],
		];
	}

	public function init()
	{
		if (!self::$newCategory && !self::$salesCategory) {
			LexemaHelper::addCategorySettings();
			$newProductCategoryId = Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID');
			$newProductCategory = ProductCategory::find()
				->where(['id' => $newProductCategoryId])
                                ->limit(1)
				->one();

			$salesProductCategoryId = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
			$salesProductCategory = ProductCategory::find()
				->where(['id' => $salesProductCategoryId])
                                ->limit(1)
				->one();


			if (isset($newProductCategory->id)) {
				self::$newCategory = $newProductCategory;
			} else {
				throw new \Exception('Ошибка настройки стандартной категории "Новинки".');
			}

			if (isset($salesProductCategory->id)) {
				self::$salesCategory = $salesProductCategory;
			} else {
				throw new \Exception('Ошибка настройки стандартной категории "Распродажа"');
			}
		}

		parent::init();
	}

	public function setTitle($key, $value)
	{
		$this->title = trim($value);
		$this->status = Product::STATUS_PUBLISHED;
	}

	public function setDbCategory($key, $value)
	{
		if (!$value) {
			return;
		}

		$dbCategory = LexemaProductCategory::findByGuid($value);

		if (!$dbCategory) {
			return;
		}

		$this->_category = $dbCategory;
	}

	public function setDbContent($key, $value)
	{
		$note = str_replace(PHP_EOL, "<br>", $value);
		$this->content = $note;
	}

	public function setDbManufacturer($key, $value)
	{
		if (!$value) {
			return;
		}

		$dbManufacturer = LexemaManufacturer::findByGuid($value);

		if (!$dbManufacturer) {
			$apiManufacturer = ManufacturerRepository::get()
				->find(['GlobalBrendId' => $value])
                                //->limit(1)
				->one();

			if ($apiManufacturer) {
				$dbManufacturer = new LexemaManufacturer($apiManufacturer);
				$dbManufacturer->save();
			}
		}

		if (isset($dbManufacturer->id)) {
			$this->manufacturerId = $dbManufacturer->id;
		}
	}

	public function setUnit($key, $value)
	{
		if (!$value) {
			return;
		}

		$dbUnit = LexemaUnit::findByGuid($value);

		if (!$dbUnit) {
			$apiUnit = UnitRepository::get()
				->find(['GlobalId' => $value])
                                ->limit(1)
				->one();

			if ($apiUnit) {
				$dbUnit = new LexemaUnit($apiUnit);
				$dbUnit->save();
			}
		}

		if (isset($dbUnit->id)) {
			$this->_unit = $dbUnit;
		}
	}

	public function setDbBalances()
	{
		if ($this->guid) {
			$balances = StorageBalanceRepository::get()
				->find(['materialGlobalId' => $this->guid])
				->all();

			if (!$balances) {
				return;
			}

			LexemaStorageBalance::setCurrentProduct($this);
			foreach ($balances as $balance) {

				if (!$balance['StorageGlobalId']) {
					// в ценах прайс группы бывает null
					// так что добавлю тут на всякий
					continue;
				}

				$dbStorage = LexemaStorage::findByGuid($balance['StorageGlobalId']);

				if (!$dbStorage) {
					$apiStorage = StorageRepository::get()
						->find(['globalId' => $balance['StorageGlobalId']])
                                                //->limit(1)
						->one();

					if ($apiStorage) {
						$dbStorage = new LexemaStorage($apiStorage);
						$dbStorage->save(false);
					}
				}

				if (!isset($dbStorage->id)) {
					// нет такого склада ни в БД ни в АПИ
					// ну, увы...
					continue;
				}

				/** @var LexemaStorageBalance $dbBalance */
				$dbBalance = LexemaStorageBalance::find()
					->where(['productId' => $this->id])
					->andWhere(['storageId' => $dbStorage->id])
                                        ->limit(1)
					->one();

				if ($dbBalance) {
					$dbBalance->loadFromRemote($balance);
					$dbBalance->save();
				} else {
					$dbBalance = new LexemaStorageBalance($balance);
					$dbBalance->save();
				}
			}
		}
	}

	public function setDbPrice()
	{
		if ($this->guid) {
			$prices = PriceRepository::get()
				->find(['ProductGlobalId' => $this->guid])
				->all();

			if (!$prices) {
				return;
			}

			LexemaProductPrice::setCurrentProduct($this);
			foreach ($prices as $price) {
				if (!$price['TypeZenaGlobalId']) {
					// иногда бывает в лексеме прайсгруппа приходит null
					// ...
					continue;
				}
				$dbPriceGroup = LexemaPriceGroup::findByGuid($price['TypeZenaGlobalId']);

				if (!$dbPriceGroup) {
					$apiPriceGroup = PriceGroupRepository::get()
						->find(['GlobalId' => $price['TypeZenaGlobalId']])
                                                ->limit(1)
						->one();

					if ($apiPriceGroup) {
						$dbPriceGroup = new LexemaPriceGroup($apiPriceGroup);
						$dbPriceGroup->save(false);
					}
				}

				if (!isset($dbPriceGroup->id)) {
					// нету такой прайсгруппы НИ В БД НИ В АПИ!
					// так что...
					continue;
				}

				/** @var LexemaProductPrice $dbProductPrice */
				$dbProductPrice = LexemaProductPrice::find()
					->where(['productId' => $this->id])
					->andWhere(['productPriceGroupId' => $dbPriceGroup->id])
                                        ->limit(1)
					->one();

				if ($dbProductPrice) {
					$dbProductPrice->loadFromRemote($price);
					$dbProductPrice->save(false);
				} else {
					$dbProductPrice = new LexemaProductPrice($price);
					$dbProductPrice->save(false);
				}
			}
		}
	}

	public function setDbAssociated()
	{
		if (!$this->guid) {
			return;
		}

		$assocs = AssociatedRepository::get()
			->find(['parentProductglobalId' => $this->guid])
			->all();

		if (!$assocs) {
			return;
		}

		foreach ($assocs as $apiAssoc) {
			$dbChildProduct = LexemaProduct::findByGuid($apiAssoc['ProductglobalId']);

			if (!$dbChildProduct) {
				$apiChildProduct = ProductRepository::get()
					->findByProductGuid($apiAssoc['ProductglobalId'])
                                        ->limit(1)
					->one();

				if ($apiChildProduct) {
					$dbChildProduct = new LexemaProduct($apiChildProduct);
					$dbChildProduct->save();
				}
			}

			if (isset($dbChildProduct->id)) {
				$apiAssoc['ProductglobalId'] = $dbChildProduct;
				$apiAssoc['parentProductglobalId'] = $this;
				$dbAssociated = new LexemaProductAssociated($apiAssoc);
				$dbAssociated->save();
			}
		}
	}

	public function setDbAnalogue()
	{
		if (!$this->guid) {
			return;
		}

		$analogues = AnalogueRepository::get()
			->find(['parentProductglobalId' => $this->guid])
			->all();

		if (!$analogues) {
			return;
		}

		foreach ($analogues as $apiAnalogue) {
			$dbChildProduct = LexemaProduct::findByGuid($apiAnalogue['ProductglobalId']);

			if (!$dbChildProduct) {
				$apiChildProduct = ProductRepository::get()
					->findByProductGuid($apiAnalogue['ProductglobalId'])
                                        ->limit(1)
					->one();

				if ($apiChildProduct) {
					$dbChildProduct = new LexemaProduct($apiChildProduct);
					$dbChildProduct->save();
				}
			}

			if (isset($dbChildProduct->id)) {
				$apiAnalogue['ProductglobalId'] = $dbChildProduct;
				$apiAnalogue['parentProductglobalId'] = $this;
				$dbAnalogue = new LexemaProductAnalogue($apiAnalogue);
				$dbAnalogue->save();
			}
		}
	}

	public function setIsSale()
	{
		$sale = SalesRepository::get()
			->find(['TovarglobalId' => $this->guid])
                        //->limit(1)
			->one();

		if ($sale) {
			$this->link('categories', self::$salesCategory);
		} else {
			// тут если что можно убирать скидочный товар, если в апи нет этого гуида
		}
	}

	public function setDbAttributes($key, $value)
	{
		if (!$this->apiParams) {
			$this->apiParams = new \stdClass();
		}
		$this->apiParams->$key = $value;
	}

	public function setDbFiles()
	{
		if (!$this->guid) {
			return;
		}

		$files = ProductFileRepository::get()
			->findByProductId($this->guid)
			->all();

		if ($files) {
			foreach ($files as $file) {
				$dbFile = new LexemaFile();
				$dbFile->currentProduct = $this;
				$dbFile->loadFromRemote($file);
				$dbFile->save();
			}
		}
	}

	public function beforeValidate()
	{
		if (!$this->_category) {
			return false;
		}

		if ($this->apiParams) {
			$params = Json::encode($this->apiParams);
			$this->params = $params;
		}

		return parent::beforeValidate();
	}

	public function afterSave( $insert, $changedAttributes )
	{
		if ($this->saved) {
			return;
		}

		if ($this->_new === 1) {
			$link = ProductToCategory::find()
				->where(['productId' => $this->id])
				->andWhere(['categoryId' => self::$newCategory->id])
                                ->limit(1)
				->one();

			if (!$link) {
				$this->link('categories', self::$newCategory);
			}
		}

		if ($this->_category) {
			$link = ProductToCategory::find()
				->where(['productId' => $this->id])
				->andWhere(['categoryId' => $this->_category])
                                ->limit(1)
				->one();

			if (!$link) {
				$this->link('categories', $this->_category);
			}
		}

		if ($this->_unit) {
			$dbProductUnit = ProductUnit::find()
				->where(['productId' => $this->id])
                                ->limit(1)
				->one();

			if ($dbProductUnit) {
				$dbProductUnit->unitId = $this->_unit->id;
				$dbProductUnit->save();
			} else {
				$dbProductUnit = new ProductUnit();
				$dbProductUnit->productId = $this->id;
				$dbProductUnit->unitId = $this->_unit->id;
				$dbProductUnit->rate = 1;
				$dbProductUnit->save();
			}
		}

		if ($this->title == "" || $this->title == " ") {
			dump($this);exit;
			//throw new \Exception('wtf');
		}

		$this->setDbBalances();
		$this->setDbPrice();
		//$this->setDbAssociated();
		//$this->setDbAnalogue();
		$this->setIsSale();

		// TODO отключил (с ними долго)
		//$this->setDbFiles();

		if (!$this->slug) {
			$this->slug = $this->id . '_' . StringHelper::translit($this->title);
			$this->saved = true;
			$this->save(false);
		}

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}
}