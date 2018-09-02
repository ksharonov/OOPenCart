<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\Product;
use app\models\db\Unit;
use app\modules\lexema\api\base\Mapper;
use function GuzzleHttp\Psr7\str;
use function PHPSTORM_META\type;

class LexemaCard extends \app\models\db\LexemaCard
{
	use Mapper;

	public $_fio;

	public $label = 'Карта';
	public $labelAttribute = 'number';

	public $map = [
		'CardNumber' => 'number',
		'CardType' => 'checkCardType',
		'Bonus' => 'discountValue',
		'BonusSumRashod' => 'bonuses',
		//'OrdersSum' => 'amountPurchases',
		'Phone' => 'setPhone',
		'Surname' => 'setFio',
		'Name' => 'setFio',
		'Patronymic' => 'setFio',
	];

	public function rules()
	{
		return [
			[['type'], 'required'],
		];
	}

	public function setFio($key, $value)
	{
		$part = mb_strtoupper(mb_substr($value, 0, 1)) . mb_substr($value, 1);

		$this->_fio[] = $part;

		if (count($this->_fio) == 3) {
			$this->fio = implode(" ", $this->_fio);
		}
	}

	public function setPhone($key, $value)
	{
		$this->phone = preg_replace("/[^0-9]/", '', $value);
	}

	public function checkCardType($key, $value)
	{
		if ($value === 47176) {
			return;
		} else {
			$this->type = $value;
		}
	}
}