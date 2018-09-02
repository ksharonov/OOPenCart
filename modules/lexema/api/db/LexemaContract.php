<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\helpers\LexemaHelper;
use app\models\db\Client;
use app\models\db\Contract;
use app\models\db\File;
use app\models\db\User;
use app\modules\lexema\api\base\Mapper;
use app\modules\lexema\api\repository\ClientRepository;
use app\modules\lexema\api\repository\DocumentRepository;
use app\modules\lexema\api\repository\PriceGroupRepository;
use yii\helpers\Json;

class LexemaContract extends Contract
{
	use Mapper;

	public $label = 'Контракт';
	public $labelAttribute = 'number';

	public $fileData;
	public $fileName;

	public $vcode;
	public $tdoc;

	public $dbClient;

	public $map = [
		'vcode' => 'vcode',
		'tdoc' => 'tdoc',
		'contractorGlobalID' => null,
		'ContractId' => 'guid',
		'name' => null,
		'Nomer' => 'number',
		'Rdate' => null,
		'BegDate' => 'setBeginDate',
		'EndDate' => 'setEndDate',
		'Fopl' => null,
		'pfopl' => null,
		'DayOpl' => null,
		'TypeZenaGlobalID' => null,
		'WithNDS' => null,
		'DogovorFileImage' => null,
	];

	public function setBeginDate($key, $value)
	{
		if (isset($value['$value'])){
			$this->dtStart = LexemaHelper::dateConvert($value['$value']);
		}
	}

	public function setEndDate($key, $value)
	{
		if (isset($value['$value'])) {
			$this->dtEnd = LexemaHelper::dateConvert($value['$value']);
		}
	}

	public function setClient($key, $value)
	{
		if (isset($this->dbClient->id)) {
			$this->clientId = $this->dbClient->id;
		}
	}

	public function getApiFiles()
	{
		$docs = DocumentRepository::get()
			->findByVcodeAndTdoc($this->vcode, $this->tdoc)
			->all();

		if (!$docs) {
			return;
		}

		foreach ($docs as $doc) {
			$fileData = $doc['file1'] ?? null;
			$fileName = $doc['fname1'] ?? null;

			if ($fileName && $fileData) {
				$attributes = [$fileName, $fileData, File::TYPE_DOCUMENT];

				$dbFile = new LexemaFile();
				$dbFile->loadModel($this, $attributes);
				$dbFile->save();
			}
		}
	}

	public function afterSave( $insert, $changedAttributes )
	{
		$this->getApiFiles();

		$this->output($insert, $changedAttributes);
		parent::afterSave($insert, $changedAttributes);
	}

}