<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27.03.2018
 * Time: 17:32
 */

namespace app\helpers;


use app\models\db\CityOnSite;
use app\models\db\OrderContent;
use app\models\db\Product;
use app\models\db\ProductCategory;
use app\models\db\ProductPriceGroup;
use app\models\db\Setting;
use app\models\db\Storage;
use app\system\db\ActiveRecord;

class LexemaHelper
{
    /** Конвертация Лексемовской даты в ЧЕЛОВЕЧЕСКУЮ (и обратно)
     * @param $date
     * @param $toHuman
     * @return array|string
     */
    public static function dateConvert($date, $toHuman = true)
    {
        if ($toHuman) {
            $pDate = explode(".", $date);
            $pDate[1] += 1;
            $pDate = implode("-", $pDate);
            $pDate = date_create($pDate)->format('Y-m-d H:i:s');
        } else {
            $pDate = explode(".", $date);
            $pDate[1] -= 1;
            $pDate = implode(".", $pDate);
        }

        return $pDate;
    }

    /** Декодирование и запись в файл base64-кодированных данных
     * @param $outputfile
     * @param $data
     * @return bool|int
     */
    public static function base64ToFile($outputfile, $data) {
        $ifp = fopen( $outputfile, "wb" );
        $result = fwrite( $ifp, base64_decode( $data ) );
        fclose( $ifp );
        return $result;
    }

    /** Сериализация/десериализация массива (для сравнения многомерных массивов)
     * @param $array
     * @param bool $unserialize
     */
    public static function prepareArray(&$array, $unserialize = false)
    {
        if ($unserialize == true) {
            foreach ($array as &$value) {
                $value = unserialize($value);
            }
        } else {
            foreach ($array as &$value) {
                $value = serialize($value);
            }
        }
    }

    public static function compareOrderContent($orderId, $apiData, $dbData)
    {
        if (is_array($apiData) && is_array($dbData) && !is_null($orderId)) {
            if (count($dbData) >= count($apiData)) {
                while ($dbData) {
                    $ok = false;
                    /** @var OrderContent $current */
                    $current = array_shift($dbData);

                    foreach ($apiData as $key =>$apiOrderContent) {
                        if (strtolower($current->product->guid) == strtolower($apiOrderContent['materialGlobalId'])) {
                            $ok = true;
                            $current->count = $apiOrderContent['kolvo'];
                            $current->priceValue = $apiOrderContent['ozena2'];
                            $current->save();
                            unset($apiData[$key]);
                        }
                    }

                    if ($ok === false) {
                        $current->delete();
                    }
                }
            } else if (count($apiData) > count($dbData)) {
                while ($apiData) {
                    $ok = false;
                    $current = array_shift($apiData);

                    foreach ($dbData as $key => $dbOrderContent) {
                        if (strtolower($current['materialGlobalId']) == strtolower($dbOrderContent->product->guid)) {
                            $ok = true;
                            /** @var OrderContent $dbOrderContent */
                            $dbOrderContent->count = $current['kolvo'];
                            $dbOrderContent->priceValue = $current['ozena2'];
                            $dbOrderContent->save();
                            unset($dbData[$key]);
                        }
                    }

                    if ($ok === false) {
                        $product = Product::findByGuid($current['materialGlobalId']);
                        if (!$product) {
                            $message = "Ошибка обновления заказа: товара с GUID " . $current['materialGlobalId']
                                . " нет в БД";
                            throw new \Exception($message);
                        } else {
                            $dbOrderContent = new OrderContent();
                            $dbOrderContent->fromRemote = true;
                            $dbOrderContent->orderId = $orderId;
                            $dbOrderContent->productId = $product->id;
                            $dbOrderContent->priceValue = $current['ozena2'];
                            $dbOrderContent->count = $current['kolvo'];
                            $dbOrderContent->save();
                        }
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public static function addCategorySettings()
	{
		$params = [
			'На главной' => ['-10', 'PRODUCT.LIST.MAIN.CATEGORY.ID'],
			'Новинки' => ['-20', 'PRODUCT.LIST.NEW.CATEGORY.ID'],
			'Распродажа' => ['-30', 'PRODUCT.LIST.DISCOUNT.CATEGORY.ID'],
			'Продукция поставщиков' => ['-2', 'PRODUCT.PRODUCER.CATEGORY.ID'],
			'Хиты продаж' => [null, 'PRODUCT.LIST.BEST.CATEGORY.ID'],
		];

		foreach ($params as $title => $config) {
			$dbCat = ProductCategory::find()
				->where(['title' => $title])
				->one();

			if (!$dbCat) {
				$dbCat = new ProductCategory();
				$dbCat->title = $title;
				$dbCat->parentId = $config[0];
				$dbCat->isDefault = true;
				$dbCat->save();


			}

			Setting::set($config[1], $dbCat->id);
		}
	}

	public static function addPriceGroupSettings()
	{
		$params = [
			'fc5986f4-d8d8-4578-a419-16ef0a16d5d2' => 'DEFAULT.PRICE.ID',
			'291bf769-a873-11e3-8b88-008048319a51' => 'WHOLESALE.PRICE.ID',
		];

		foreach ($params as $guid => $key) {
			$dbPriceGroup = ProductPriceGroup::findByGuid($guid, true);

			if (!$dbPriceGroup) {
				throw new \Exception('Ошибка обновления настроек. Прайс ' . $guid . ' не обнаружен в БД.');
			}

			Setting::set($key, $dbPriceGroup->id);
		}
	}

	public static function refreshCitiesOnSite()
	{
		$cities = Storage::find()
			->select('cityId')
			->distinct()
			->where(['type' => Storage::TYPE_SHOP])
			->andWhere('cityId IS NOT NULL')
			->all();

		CityOnSite::deleteAll();

		foreach ($cities as $city) {
			$tCity = new CityOnSite();
			$tCity->cityId = $city->cityId;
			$tCity->save();
		}
	}
}