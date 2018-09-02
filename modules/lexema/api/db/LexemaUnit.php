<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 11:26
 */

namespace app\modules\lexema\api\db;


use app\models\db\Product;
use app\models\db\Unit;
use app\modules\lexema\api\base\Mapper;

class LexemaUnit extends Unit
{
	use Mapper;

	public $label = 'Единица измерения';
	public $labelAttribute = 'title';

	public $map = [
		'GlobalId' => 'guid',
		'name' => 'title',
	];
}