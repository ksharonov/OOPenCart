<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 21.05.2018
 * Time: 15:26
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class ContractRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_GetReestrContract',
	];

	/**
	 * Поиск по гуиду клиента,
	 * Т.к. запрос без параметров ничего не выдает
	 * @param $guid
	 * @return ContractRepository
	 */
	public function findByContractorGuid($guid)
	{
		$data = $this->source->request($this->url['model'] . '&Contractor=' . $guid);
		$this->data['index'] = json_decode($data, true);
		$this->foundData = 'index';
		return $this;
		//return reset($this->data['index']) ?? null;
	}
}