<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 30.05.2018
 * Time: 16:17
 */

namespace app\modules\lexema\api;


use app\modules\lexema\api\base\IConnection;
use app\modules\lexema\api\connection\CookieAuthConnection;

class Balance
{
	protected $source;

	public function __construct(IConnection $source)
	{
		if ($source) {
			$this->source = $source;
		} else {
			$this->source = CookieAuthConnection::get();
		}
	}

	public function update()
	{
		$result = $this->getUpdates();

		$fname = $this->logsPath . "balance.log";

		if (!$result) {
			$message = date('Y-m-d H:i:s') . " Изменений нет. " . "\r\n\r\n";
			echo $message;
			@file_put_contents($fname, $message, FILE_APPEND);
			return;
		}

		$resultTotal = [];
		$count = 0;
		do {
			$resultTotal = $resultTotal + $result[2];
			if ($count > 3) {
				$message = date('Y-m-d H:i:s') . " Превышено максимальное число итераций." . "\r\n";
				echo $message;
				$log[] = $message;
				break;
			}
			$result = $this->getUpdates($result[0], $result[1]);
			$count++;
		} while ($result);

		if ($resultTotal) {
			$this->importStorageBalance($resultTotal);
		}

		$message = date('Y-m-d H:i:s') . " Обновление остатков завершено. Обновлений: " . count($resultTotal) . "\r\n";
		echo $message;
		$log[] =  $message;

		$string = "";
		foreach ($log as $row) {
			$string .= $row;
		}

		@file_put_contents($fname, $string . "\r\n", FILE_APPEND);
	}

	public function getUpdates($key1 = null, $key2 = null)
	{
		$query = null;
		$result = null;

		if ($key1 !== null || $key2 !== null) {
			$query = "&Key1=$key1&Key2=$key2";
		}

		$changes = $this->source->request("ESI_GetChangeStorage&Mode=1" . $query);

		if ($changes) {
			$result = [
				$changes[0]['key1'],
				+$changes[0]['key2'],
				$changes
			];
		}

		return $result;
	}
}