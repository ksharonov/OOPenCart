<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 16.05.2018
 * Time: 11:49
 */

namespace app\modules\lexema\api\repository;



use app\modules\lexema\api\base\BaseRepository;
use app\modules\lexema\api\connection\HttpConnection;

class ProductRepository extends BaseRepository
{
	protected $url = [
		'type' => HttpConnection::TYPE_QUERY,			// можно не указывать
		'namespace' => HttpConnection::NAMESPACE_ESI,	// можно не указывать
		'model' => 'ESI_GetProduct',
		'params' => [
			''
		]
	];

	/**
	 * @param $guid
	 * @return ProductRepository
	 */
	public function findByProductGuid($guid)
	{
		$data = $this->source->request($this->url['model'] . "&FolderglobalId={$guid}");
		$this->data['index'] = json_decode($data, true);
		$this->foundData = 'index';
		return $this;
	}
}