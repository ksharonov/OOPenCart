<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\helpers\LexemaHelper;
use app\helpers\ModelRelationHelper;
use app\models\db\Address;
use app\models\db\City;
use app\models\db\Storage;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\StorageRepository;

/**
 * Class LexemaShop
 * @package app\modules\lexema\api\db
 */
class LexemaShop extends Storage
{
	use Mapper;

	public $label = 'Магазин';
	public $labelAttribute = 'title';

	public $cityParams = [];

	public $map = [
		'globalId' => 'guid',
		'name' => 'title',
		'adress' => 'setAddress',
		'phone' => 'setphone',
		'CityGlobalId' => null,
		'CityName' => 'setAddress',
		'PrimaryStorage' => 'isMain',
	];

	public function setPhone($key, $value)
	{
		$this->phone = trim(str_replace("тел.", "", $value));
	}

	public function setAddress($key, $value)
	{
		$this->cityParams[$key] = $value;
	}


	public function beforeSave( $insert )
	{
		$cityName = $this->cityParams['CityName'] ?? null;

		$dbCity = City::find()
			->where(['title' => $cityName])
			->one();

		$this->cityParams['cityObj'] = $dbCity;

		$this->cityId = $dbCity->id ?? null;
		$this->type = Storage::TYPE_SHOP;
		$this->status = Storage::STATUS_ACTIVE;

		return parent::beforeSave($insert);
	}

	public function afterSave( $insert, $changedAttributes )
	{
		if (!is_null($this->cityParams['adress']) &&
			!is_null($this->cityParams['cityObj'])) {

			$dbAddress = Address::find()
				->where(['relModel' => ModelRelationHelper::REL_MODEL_STORAGE])
				->andWhere(['relModelId' => $this->id])
				->one();

			if (!$dbAddress) {
				$address = explode(",", $this->cityParams['adress']);
				array_shift($address);
				$address = implode(",", $address);

				$tCity = $this->cityParams['cityObj'];

				$tAddress = new Address();
				$tAddress->countryId = $tCity->countryId;
				$tAddress->regionId = $tCity->regionId;
				$tAddress->cityId = $tCity->id;
				$tAddress->address = $address;
				$tAddress->relModel = ModelRelationHelper::REL_MODEL_STORAGE;
				$tAddress->relModelId = $this->id;
				$tAddress->save();
			}
		}

		LexemaHelper::refreshCitiesOnSite();

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}

}