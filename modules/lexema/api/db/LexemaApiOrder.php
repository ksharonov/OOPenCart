<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\Client;
use app\models\db\File;
use app\models\db\User;
use app\models\db\UserToClient;
use app\modules\lexema\api\base\Mapper;
use app\models\db\Order;
use app\modules\lexema\api\repository\ContractRepository;
use app\modules\lexema\api\repository\DocumentRepository;
use app\modules\lexema\api\repository\OrderRepository;
use app\modules\lexema\api\repository\PriceGroupRepository;
use app\modules\lexema\api\repository\UserRepository;
use yii\helpers\Json;

/**
 * Class LexemaOrder
 * @package app\modules\lexema\api\db
 */
class LexemaApiOrder extends Order
{
	use Mapper;

	public $label = 'Заказ';
	public $labelAttribute = 'title';

	public $vcode;
	public $tdoc;

	public $orderNumber;

	public $apiUserData;

	public $apiPaymentData;

	public $apiDeliveryData;

	public $map = [
		'vcode' => 'vcode',
		'tdoc' => 'tdoc',
		'OrderId' => 'orderNumber',
		'OrderDateTime' => 'dtCreate',
		' Login' => 'setDbUser',
		' FIO' => 'setUserData',
		'LoyalCardNumber' => 'setCardData',
		'Operator' => 'setPaymentData',
		' TransactionId' => 'setPaymentData',
		'PaymentAmount' => 'setPaymentData',
		'TransactionDateTime' => 'setPaymentData',
		'Information' => null,
		'DeliveryMethod' => 'setDeliveryData',
		'DeliveryAddress' => 'setDeliveryData',
		'DeliveryCost' => 'setDeliveryData',
		'ContactPerson' => 'setUserData',
		'ContactPhone' => 'setUserData',
		'Comment' => 'comment',
		'GlobalIdDeban' => 'setClient',
		'Items' => null,
	];

	// AC643C5A-7D3D-4769-9F6C-E2607BB5BBAA физик

	public function setDbUser($key, $value)
	{
		if ($value) {
			$dbUser = User::find()
				->where(['username' => $value])
				->one();

			if ($dbUser) {
				$this->userId = $dbUser->id;
				$this->apiUserData['username'] = $value;
			}
		}
	}

	/*
	 * {"username":"test","email":"test@test.ru","fio":"Test Test","phone":"888005553545"}
	 */

	public function setUserData($key, $value)
	{
		if ($key == ' FIO' && $value) {
			$this->apiUserData['fio'] = $value;
		}

		if ($key == 'ContactPerson' && $value) {
			$this->apiUserData['name'] = $value;
		}

		if ($key == 'ContactPhone' && $value) {
			$this->apiUserData['phone'] = $value;
		}
	}

	public function setPaymentData($key, $value)
	{

	}

	public function setDeliveryData($key, $value)
	{

	}

	// TODO новые доки
	public function getDocumentFiles()
	{
		if (!$this->vcode) {
			return;
		}

		$apiFiles = DocumentRepository::get()
			->findByVcodeAndTdoc($this->vcode, DocumentRepository::TDOC_EZK)
			->all();

		if (!$apiFiles) {
			return;
		}

		foreach ($apiFiles as $apiFile) {
			$attributes = [$apiFile['fname1'], $apiFile['file1'], File::TYPE_DOCUMENT];

			$dbFile = new LexemaFile();
			$dbFile->loadModel($this, $attributes);
			$dbFile->save();
		}
	}

	// TODO товары в заказе
	public function getOrderItems()
	{

	}

	public function beforeSave( $insert )
	{
		if (!$insert) {
			$this->source = Order::SOURCE_LEXEMA;
		}

		return parent::beforeSave($insert); // TODO: Change the autogenerated stub
	}

	public function afterSave( $insert, $changedAttributes )
	{

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}
}