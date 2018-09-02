<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 12:01
 */

namespace app\modules\lexema\api\base;


interface IConnection
{
	public static function get();

	public function request($url);

	public function send($url, $data, $method);
}