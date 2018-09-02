<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 21.05.2018
 * Time: 16:45
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class StorageBalanceRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_GetChangeStorage&Mode=0',
	];
}