<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 31.05.2018
 * Time: 13:34
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class ReconciliationRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_ReconciliationAct',
	];

	public function findByContractorGuid($guid)
	{
		$data = $this->source->request($this->url['model'] . "&Contractor={$guid}");
		$this->data['index'] = json_decode($data, true);
		$this->foundData = 'index';
		return $this;
	}
}