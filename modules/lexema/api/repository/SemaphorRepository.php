<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 24.05.2018
 * Time: 11:58
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class SemaphorRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_GetSemaphorChanges',
	];

	/**
	 * @param $seconds
	 * @return SemaphorRepository
	 */
	public function findByPassedTime($seconds)
	{
		$data = $this->source->request($this->url['model'] . "&DepthDate={$seconds}");
		$this->data['index'] = json_decode($data, true);
		$this->foundData = 'index';
		return $this;
	}
}