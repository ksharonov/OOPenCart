<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 06.06.2018
 * Time: 9:49
 */

namespace app\modules\lexema\api;

use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\Setting;
use app\modules\lexema\api\db\LexemaProduct;
use app\modules\lexema\api\repository\ProductRepository;
use app\modules\lexema\api\repository\SalesRepository;


/**
 * Class CronTask
 * @package app\modules\lexema\cron
 */
class CronTask
{
	/**
	 * Синхронизация скидок
	 * @throws \ReflectionException
	 * @throws \yii\base\Exception
	 */
	public function refreshSales()
	{
		$salesId = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');
		$dbSalesCategory = ProductCategory::findOne(['id' => $salesId]);

		$dbSales = Product::find()
			->innerJoin('product_to_category ptc', "ptc.productId = product.id AND ptc.categoryId = {$dbSalesCategory->id}")
			->indexBy(function ($row) {
				return strtoupper($row['guid']);
			})
			->all();

		$apiSales = SalesRepository::get()
			->find('TovarglobalId')
			->all();

		// убираем лишнее
		foreach ($dbSales as $guid => $dbSale) {
			if (!isset($apiSales[$guid])) {
				$dbSale->unlink('categories', $dbSalesCategory, true);
				echo $dbSale->title . ' удален из категории ' . $dbSalesCategory->title . PHP_EOL;
			}
		}

		// добавляем недостающее
		foreach ($apiSales as $guid => $apiSale) {
			if (!isset($dbSales[$guid])) {
				//echo 'Товар с guid: ' . $guid . ' надо добавить в скидки.<br>';
				$dbProduct = Product::findByGuid($guid, true);

				if ($dbProduct) {
					$dbProduct->link('categories', $dbSalesCategory);
					echo $dbProduct->title . ' добавлен в категорию ' . $dbSalesCategory->title . PHP_EOL;
				}
			}
		}
	}

	/**
	 * Синхронизация новинок
	 * @throws \ReflectionException
	 * @throws \yii\base\Exception
	 */
	public function refreshNews()
	{
		$newsId = Setting::get('PRODUCT.LIST.NEW.CATEGORY.ID');
		$dbNewsCategory = ProductCategory::findOne(['id' => $newsId]);

		$dbNews = Product::find()
			->innerJoin('product_to_category ptc', "ptc.productId = product.id AND ptc.categoryId = {$dbNewsCategory->id}")
			->indexBy(function ($row) {
				return strtoupper($row['guid']);
			})
			->all();

		$apiNews = ProductRepository::get()
			->find(['IsNewProduct' => 1])
			->all();

		$apiNewsGuid = [];

		foreach ($apiNews as $apiNew) {
			$apiNewsGuid[$apiNew['globalId']] = $apiNew;
		}

		// убираем лишнее
		foreach ($dbNews as $guid => $dbNew) {
			if (!isset($apiNewsGuid[$guid])) {
				$dbNew->unlink('categories', $dbNewsCategory, true);
				echo $dbNew->title . ' удален из категории ' . $dbNewsCategory->title . PHP_EOL;
			}
		}

		// добавляем недостающее
		foreach ($apiNewsGuid as $guid => $apiNew) {
			if (!isset($dbNews[$guid])) {
				//echo 'Товар с guid: ' . $guid . ' надо добавить в скидки.<br>';
				$dbProduct = Product::findByGuid($guid, true);

				if ($dbProduct) {
					$dbProduct->link('categories', $dbNewsCategory);
					echo $dbProduct->title . ' добавлен в категорию ' . $dbNewsCategory->title . PHP_EOL;
				} else {
					continue;
					$apiProduct = ProductRepository::get()
						->find(['globalId' => $apiNew['globalId']])
						->one();

					if ($apiProduct) {
						$dbProduct = new LexemaProduct($apiProduct);
						if ($dbProduct->validate() && $dbProduct->save()) {
							$dbProduct->link('categories', $dbNewsCategory);
							echo $dbProduct->title . ' добавлен в категорию ' . $dbNewsCategory->title . PHP_EOL;
						}
					}
				}
			}
		}
	}
}