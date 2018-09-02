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
use app\models\db\Storage;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\ShopRepository;

/**
 * Class LexemaShop
 * @package app\modules\lexema\api\db
 */
class LexemaStorage extends Storage
{
	use Mapper;

	public $label = 'Склад';
	public $labelAttribute = 'title';

	public $cityParams = [
		];

	/** @var LexemaShop $parentShop */
	public $parentShop;

	public $map = [
		'globalId' => 'guid',
		'name' => 'title',
		'adress' => 'setAddress',
		'ParentGlobalIDShop' => 'setShop',
	];

	public function setShop($key, $value)
	{
		if (!is_null($value)) {
			$dbShop = LexemaShop::findByGuid($value);
			if (!$dbShop) {
				$apiShop = ShopRepository::get()
					->find(['globalId' => strtoupper($value)])
					->one();
				if ($apiShop) {
					$dbShop = new LexemaShop($apiShop);
					$dbShop->save(false);
				}
			}

			$this->parentId = $dbShop->id;
			$this->cityId = $dbShop->cityId;
			$this->parentShop = $dbShop;
		} else {
			$this->parentId = null;
		}
	}

	public function setAddress($key, $value)
	{
		$this->cityParams[$key] = (string)$value;
	}

	public function beforeSave( $insert )
	{
		$this->type = Storage::TYPE_STORAGE;
		$this->status = Storage::STATUS_ACTIVE;

		return parent::beforeSave($insert);
	}

	public function afterSave( $insert, $changedAttributes )
	{
		if (isset($this->parentShop) && $this->parentShop->address) {
			$dbAddress = new Address();
			$dbAddress->setAttributes($this->parentShop->address->attributes);
			$dbAddress->relModelId = $this->id;
			$dbAddress->save(false);
		}

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}
}