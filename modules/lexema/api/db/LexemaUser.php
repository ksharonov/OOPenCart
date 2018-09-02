<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\User;
use app\models\db\UserToClient;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\connection\FileConnection;
use app\modules\lexema\api\repository\ClientRepository;

class LexemaUser extends User
{
	use Mapper;

	public $label = 'Пользователь';
	public $labelAttribute = 'username';

	public $_fio;

	public $_client;

	public $map = [
		'ContractorGlobalId' => 'guid',
		'ContractGlobalId' => null,
		'StatusDoc' => null,
		'date1' => null,
		'date2' => null,
		'Fam' => 'setfio',
		'Im' => 'setfio',
		'Otch' => 'setfio',
		'eMail' => 'email',
		'Phone' => 'setphone',
		'LoginName' => 'username',
	];

	public function setPhone($key, $value)
	{
		$this->phone = preg_replace("/[^()-+0-9 ]/", '', $value);
		$this->status = User::STATUS_ACTIVE;
	}

	public function setFio($key, $value)
	{
		$part = mb_strtoupper(mb_substr($value, 0, 1)) . mb_substr($value, 1);

		$this->_fio[] = $part;

		if (count($this->_fio) == 3) {
			$this->fio = mb_ereg_replace("[^а-яА-Я\s]", '', implode(" ", $this->_fio));
		}
	}

	public function setDbClient()
	{
		if ($this->_client) {
			$dbClient = $this->_client;
		} else {
			$dbClient = LexemaClient::findByGuid($this->guid);

			if (!$dbClient) {
				$apiClient = ClientRepository::get()
					->find(['ContractorGlobalId' => $this->guid])
					->one();

				if ($apiClient) {
					$dbClient = new LexemaClient();
					$dbClient->_user = $this;
					$dbClient->loadFromRemote($apiClient);
					$dbClient->save(false);
				}
			}
		}

		if (isset($dbClient->id)) {
			UserToClient::bind($this, $dbClient);
		}
	}

	public function afterSave( $insert, $changedAttributes )
	{
		$this->setDbClient();

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}
}