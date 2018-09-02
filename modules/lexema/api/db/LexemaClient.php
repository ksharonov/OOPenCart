<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\Client;
use app\models\db\User;
use app\models\db\UserToClient;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\connection\FileConnection;
use app\modules\lexema\api\repository\ContractRepository;
use app\modules\lexema\api\repository\PriceGroupRepository;
use app\modules\lexema\api\repository\UserRepository;
use yii\helpers\Json;

class LexemaClient extends Client
{
	use Mapper;

	public $label = 'Клиент';
	public $labelAttribute = 'title';

	public $paramsArray;

	public $priceGroupGuid;

	public $_contract;

	public $_user;

	public $specialPricePerm;

	public $map = [
		'ContractorGlobalId' => 'guid',
		'ContractGlobalId' => '_contract',
		'FullName' => null,
		'Name' => 'title',
		'PostAdress' => 'setParams',
		'LawAdress' => 'setParams',
		'phone' => 'setphone',
		'email' => 'email',
		'Inn' => 'setParams',
		'Kpp' => 'setParams',
		'ContractName' => null,
		'daysofdelay' => 'setParams',
		'ResolOnSpecZena' => 'specialPricePerm',
		'typezena' => 'priceGroupGuid',
		'PlatRekvizit' => 'setParams',
		'Summa' => 'setParams',
		'SummaLastPlat' => 'setParams',
		'DateLastPlat' => 'setParams',
		'SummaKredLimit' => 'setParams',
		'manager' => 'setParams',
		'manageremail' => 'setParams',
		'managerphone' => 'setParams',
	];

	public function setPhone($key, $value)
	{
		$this->phone = preg_replace("/[^()+0-9,]/", '', $value);
		$this->status = Client::STATUS_ACTIVE;
	}

	public function setContract()
	{
		if (!$this->_contract) {
			return;
		}

		$dbContract = LexemaContract::findByGuid($this->_contract);

		if (!$dbContract) {
			$apiContract = ContractRepository::get()
				->findByContractorGuid($this->guid)
				->one();

			if ($apiContract) {
				$dbContract = new LexemaContract($apiContract);
				$dbContract->dbClient = $this;
				$dbContract->save(false);
			}
		}
	}

	public function setUser()
	{
		if ($this->_user) {
			$dbUser = $this->_user;
		} else {
			$dbUser = LexemaUser::findByGuid($this->guid);

			if (!$dbUser) {
				$apiUser = UserRepository::get()
					->find(['ContractorGlobalId' => $this->guid])
					->one();

				if ($apiUser) {
					$dbUser = new LexemaUser();
					$dbUser->_client = $this;
					$dbUser->loadFromRemote($apiUser);
					$dbUser->save(false);
				}
			}
		}

		if (isset($dbUser->id)) {
			UserToClient::bind($dbUser, $this);
		}
	}

	public function setPriceGroup()
	{
		if (!$this->priceGroupGuid) {
			return;
		}

		$dbPriceGroup = LexemaPriceGroup::findByGuid($this->priceGroupGuid);

		if (!$dbPriceGroup) {
			$apiPriceGroup = PriceGroupRepository::get()
				->find(['GlobalId' => $this->priceGroupGuid])
				->one();

			if ($apiPriceGroup) {
				$dbPriceGroup = new LexemaPriceGroup($apiPriceGroup);
				$dbPriceGroup->save(false);
			}
		}

		if (isset($dbPriceGroup->id)) {
			$this->unlinkAll('priceGroup', true);

			if ($this->specialPricePerm === 1) {
				$this->link('priceGroup', $dbPriceGroup);
			}
		}
	}

	public function setParams($key, $value)
	{
		$this->paramsArray[$key] = $value;
	}

	public function beforeSave( $insert )
	{
		$params = new \stdClass();
		$params->inn = $this->paramsArray['Inn'];
		$params->kpp = $this->paramsArray['Kpp'];
		$params->postAddress = $this->paramsArray['PostAdress'];
		$params->lawAddress = $this->paramsArray['LawAdress'];
		$params->payment = $this->paramsArray['PlatRekvizit'];
		// TODO что из этого т.н. "баланс" ?
		$params->balance = $this->paramsArray['Summa'];
		//$params->paymentSumm = $this->paramsArray['Summa'];
		$params->lastPaymentSumm = $this->paramsArray['SummaLastPlat'];
		$params->lastPaymentDate = $this->paramsArray['DateLastPlat'];
		$params->postponement = $this->paramsArray['daysofdelay'];
		$params->creditLimit = $this->paramsArray['SummaKredLimit'];
		$params->manager = $this->paramsArray['manager'];
		$params->managerEmail = $this->paramsArray['manageremail'];
		$params->managerPhone = $this->paramsArray['managerphone'];
		//$params->specialPricePermission = $this->paramsArray['ResolOnSpecZena'];
		$params = Json::encode($params);

		$this->params = $params;

		return parent::beforeSave($insert); // TODO: Change the autogenerated stub
	}

	public function afterSave( $insert, $changedAttributes )
	{
		$this->setUser();
		$this->setPriceGroup();
		$this->setContract();

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}
}