<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 23.05.2018
 * Time: 12:27
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class ProductFileRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_GetProductImages',
	];

	/**
	 * @param $guid
	 * @return ProductFileRepository
	 */
	public function findByProductId($guid)
	{
		$data = $this->source->request($this->url['model'] . "&GlobalIdMaterial={$guid}");
		$this->data['index'] = json_decode($data, true);
		$this->foundData = 'index';
		return $this;
	}
}