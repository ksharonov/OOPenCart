<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 16.05.2018
 * Time: 11:49
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class SalesRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_GetSalesProduct',
	];

	/*
	 *[
	 * 'TovarglobalId' => "055f5d34-e385-11e0-9a6d-002511a5a45a"
	 *]
	 */
}