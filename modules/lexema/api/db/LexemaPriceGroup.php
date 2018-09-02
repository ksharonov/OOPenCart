<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\Product;
use app\models\db\ProductPriceGroup;
use app\models\db\Setting;
use app\models\db\Unit;
use app\modules\lexema\api\base\Mapper;

class LexemaPriceGroup extends ProductPriceGroup
{
	use Mapper;

	public $label = 'Прайс';
	public $labelAttribute = 'title';

	public $priceType = [
		'fc5986f4-d8d8-4578-a419-16ef0a16d5d2' => '0',
		'291bf769-a873-11e3-8b88-008048319a51' => '1',
		'19971ec0-a873-11e3-8b88-008048319a51' => '1',
		'B6DC1D83-8836-4634-9290-708184B24547' => '1'
	];

	public $setting = [
		'fc5986f4-d8d8-4578-a419-16ef0a16d5d2' => 'DEFAULT.PRICE.ID',
		'291bf769-a873-11e3-8b88-008048319a51' => 'WHOLESALE.PRICE.ID',
	];

	public $map = [
		'GlobalId' => 'setglobalid',
		'name' => 'settitle',
	];

	public function setGlobalId($key, $value)
	{
		$this->guid = $value;
		if (isset($this->priceType[$value])) {
			$this->clientType = (int)$this->priceType[$value];
		}
	}

	public function setTitle($key, $value)
	{
		$this->title = trim(mb_ereg_replace("[^-а-яА-Яa-zA-Z\s]", "", $value));
	}

	public function afterSave( $insert, $changedAttributes )
	{
		if (isset($this->setting[strtolower($this->guid)])) {
			Setting::set($this->setting[strtolower($this->guid)], $this->id);
		}

		$this->output($insert, $changedAttributes);
		parent::afterSave($insert, $changedAttributes);
	}
}