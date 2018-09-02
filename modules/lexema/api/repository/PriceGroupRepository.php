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

class PriceGroupRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_TypePriceZena',
	];
}