<?php

namespace app\helpers;

use app\models\db\File;
use app\models\db\ProductCategory;
use app\models\OneC\StorageOneC;
use app\models\OneC\StorageBalanceOneC;
use app\models\db\ProductAttributeGroup;
use app\models\db\ProductPriceGroup;
use app\models\db\ProductToAttribute;
use app\models\db\Product;
use app\models\OneC\ProductOneC;
use app\models\OneC\UnitOneC;
use app\models\OneC\ProductCategoryOneC;
use app\models\OneC\ProductPriceGroupOneC;
use app\models\OneC\ProductPriceOneC;
use app\models\OneC\ClientOneC;
use app\models\OneC\ImageOneC;
use app\models\OneC\TestConnectOneC;
use app\models\OneC\ProductAttributeOneC;
use app\models\OneC\ProductToAttributeOneC;
use PHPUnit\Framework\ExpectationFailedException;

class ImportHelper
{
	const READ_LEN = 4096;

    public static function Import1C()
    {

        /**
         * ========================================================================================================================
         * ===============================================Выгрузка на сайт=========================================================
         * ========================================================================================================================
         */

        $productCategor = new ProductCategoryOneC();
        $productCategor->addProductCategoryOneC();

        $unit = new UnitOneC();
        $unit->addUnitOneC();

        $model = new ProductOneC();
        $model->addProductOneC();

        $loadImage = new ImageOneC();
        $loadImage->addImageOneC();

        $attr = new ProductAttributeOneC();
        $attr->addProductAttributeOneC();

        $attr_value = new ProductToAttributeOneC();
        $attr_value->addProductToAttributeOneC();

        $product_price_group = new ProductPriceGroupOneC(); //NO DELETE
        $product_price_group->addProductPriceGroupOneC();

        $productPrice = new ProductPriceOneC();
        $productPrice->addProductPriceOneC();

        $storage = new StorageOneC();
        $storage->addStorageOneC();

        $storageBalance = new StorageBalanceOneC();
        $storageBalance->addStorageBalanceOneC();

        $client = new ClientOneC();
        $client->addClientOneC();

    }

    public static function CheckTestConnection1C()
    {
        $test = new TestConnectOneC();
        $test->addTestConnectOneC();
        return true;
    }

    public static function grab_image($url, $saveto){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if( $httpCode <> 200){
            return false;
        }
        curl_close ($ch);
        if(file_exists($saveto)){
            return false;
            //unlink($saveto);
        }
        $fp = fopen($saveto,'x');
        fwrite($fp, $raw);
        fclose($fp);
        return true;
    }

    public static function getIdSheider ($id_parent, &$sheider_id){
        $group_sheider = \app\models\db\ProductCategory::find()
            ->select('id')
            ->from('product_category')
            ->where(['parentId' => $id_parent])
            ->asArray()
            ->all();
        foreach ($group_sheider as $one_group){
            $sheider_id[] =  $one_group['id'];
            //echo $one_group['id']. '<br>';
            //print_r($sheider_id);
            self::getIdSheider($one_group['id'], $sheider_id);
        }
    }

	/**
	 * @param string $photo
	 * @param Product $product
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public static function addPhoto(string $pathFromCopy, Product $product)
	{
		$pathToCopy = \Yii::getAlias("@webroot") . "files/import/product/{$product->backCode}/";
		$filename = substr($pathFromCopy, strrpos($pathFromCopy, '/') + 1);
		$id = $product->id;

		if (!is_dir($pathToCopy)) {
			mkdir($pathToCopy, 0777, true);
		}

		if (!is_file($pathToCopy . $filename)) {
			$result = copy($pathFromCopy, $pathToCopy . $filename);

			if ($result) {
				$dbFile = File::find()
					->where(['path' => "/files/import/product/{$product->backCode}/{$filename}"])
					->one();

				if (!$dbFile) {
					$newDbFile = new File();
					$newDbFile->type = File::TYPE_IMAGE;
					$newDbFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
					$newDbFile->relModelId = $id;
					$newDbFile->status = File::FILE_PUBLISHED;
					$newDbFile->path = "/files/import/product/{$product->backCode}/{$filename}";
					$newDbFile->save();
				} else {
					throw new \Exception('Запись в БД все еще есть. Путь: ' . $dbFile->path);
				}

				echo 'Файл ' . $filename . ' добавлен к товару ' . $product->title . PHP_EOL;
			}
		} else {
			throw new \Exception('Невозможно скопировать файл - он все еще имеется: ' .
				$pathToCopy . $filename);
		}
	}

	public static function FileIdentical($fn1, $fn2)
	{
		if(filesize($fn1) !== filesize($fn2))
			return FALSE;

		if(!$fp1 = fopen($fn1, 'rb'))
			return FALSE;

		if(!$fp2 = fopen($fn2, 'rb')) {
			fclose($fp1);
			return FALSE;
		}

		$same = TRUE;
		while (!feof($fp1) and !feof($fp2))
			if(fread($fp1, ImportHelper::READ_LEN) !== fread($fp2, ImportHelper::READ_LEN)) {
				$same = FALSE;
				break;
			}

		if(feof($fp1) !== feof($fp2))
			$same = FALSE;

		fclose($fp1);
		fclose($fp2);

		return $same;
	}

}