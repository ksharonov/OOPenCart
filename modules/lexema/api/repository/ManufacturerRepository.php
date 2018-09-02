<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 22.05.2018
 * Time: 9:29
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class ManufacturerRepository extends BaseRepository
{
	protected $url = [
		'model' => 'ESI_GetProduceName',
	];
}