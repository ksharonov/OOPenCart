<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 24.05.2018
 * Time: 11:55
 */

namespace app\modules\lexema\api;


use app\models\db\LexemaOrder;
use app\models\db\Order;
use app\models\db\ProductToCategory;
use app\models\db\Setting;
use app\modules\lexema\api\base\IConnection;
use app\modules\lexema\api\connection\CookieAuthConnection;
use app\modules\lexema\api\db\LexemaApiOrder;
use app\modules\lexema\api\db\LexemaClient;
use app\modules\lexema\api\db\LexemaProduct;
use app\modules\lexema\api\repository\ClientRepository;
use app\modules\lexema\api\repository\OrderRepository;
use app\modules\lexema\api\repository\PriceRepository;
use app\modules\lexema\api\repository\ProductRepository;
use app\modules\lexema\api\repository\SemaphorRepository;

class Semaphor
{
	/**
	 * Класс интерфейса IConnection
	 * для получения данных из API
	 * @var IConnection
	 */
	protected $source;

	/**
	 * Общее количество изменений
	 * @var integer $totalChanges
	 */
	protected $totalChanges;

	/**
	 * Массив изменений
	 * в формате [
	 * 	тип => [массив изменений],
	 * 	тип2 => [массив изменений],
	 * ]
	 * @var array $changes
	 */
	protected $changes;

	/**
	 * Массив с изменениями,
	 * в которых что-то пошло не так
	 * (сохранить в файл/записать в лог)
	 * @var array
	 */
	protected $failed;

	/**
	 * Массив с успешными обновлениями
	 * @var array $successful
	 */
	protected $successful;

	/**
	 * Файл лога
	 * @var string $log
	 */
	protected $log;

	/**
	 * Файл логов ошибок
	 * @var
	 */
	protected $errorLog;

	/**
	 * Маппинг имен моделей из запроса
	 * на методы этого класса
	 * @var array
	 */
	protected $models = [
		'ESI_GetProduct' 		=> 'product',
		'ESI_GetContractor' 	=> 'client',
		'ESI_Price' 			=> 'price',
		'ESI_GetReestrEZK' 		=> 'order',
		'ESI_ReconciliationAct' => 'act',
		'ESI_GetSalesProduct' 	=> 'sale',
		'ESI_Analogue' 			=> 'analogue',
		'ESI_GetFolderProduct' 	=> 'category',
	];

	/**
	 * Semaphor constructor.
	 * @param IConnection|null $source
	 */
	public function __construct(IConnection $source = null)
	{
		if ($source) {
			$this->source = $source;
		} else {
			$this->source = CookieAuthConnection::get();
		}

		$this->log = \Yii::getAlias('@app/runtime/logs/semaphor_log.log');
		$this->errorLog = \Yii::getAlias('@app/runtime/logs/semaphor_error_log.log');
	}

	/**
	 * Публичный метод для запуска семафора
	 *
	 * @throws \yii\base\Exception
	 */
	public function update()
	{
		$interval = $this->getUpdates();

		echo "[" . date('Y.m.d H:i:s') . "]" . " Изменений: " . $this->totalChanges . " ({$interval} мин.) " .  PHP_EOL;

		if ($this->changes) {
			foreach ($this->changes as $model => $changeList) {
				$method = 'update' . ucfirst($model);
				$this->$method($changeList);
			}
		}

		if ($this->successful) {
			$this->log($this->successful, $this->log);
		}

		if ($this->failed) {
			$this->log($this->failed, $this->errorLog, true);
		}

		echo "Проверка файлов заказов..." . PHP_EOL;
		// забираем файлы заказов
		$dbOrders = LexemaApiOrder::find()
			->all();

		foreach ($dbOrders as $dbOrder) {
			$dbOrder->getVcode();

			if (!$dbOrder->vcode) {
				continue;
			}

			if ($dbOrder->files) {
				continue;
			}

			$dbOrder->getDocumentFiles();
		}

	}

	/**
	 * Получение списка обновлений за интервал
	 *
	 * @return int
	 * @throws \yii\base\Exception
	 */
	protected function getUpdates()
	{
		$lastUpdate = Setting::get('LEXEMA.LAST.UPDATE');
		$interval = (int)intdiv((time() - $lastUpdate), 60);

		$changes = SemaphorRepository::get()
			->setSource($this->source)
			->findByPassedTime($interval)
			//->findByPassedTime(2000)
			->all();

		// устанавливаем текущее время
		// как время последнего обновления
		 Setting::set('LEXEMA.LAST.UPDATE', time());

		$this->totalChanges = count($changes);

		foreach ($changes as $change) {
			if (!isset($this->models[$change['Model']])) {
				$this->failed['nomodel'][] = $change['Model'];
				continue;
			}

			$index = $this->models[$change['Model']];
			$this->changes[$index][] = $change['globalId'];
		}

		return $interval;
	}

	/**
	 * Логи
	 * @param $data
	 * @param $file
	 * @param bool $error
	 */
	protected function log($data, $file, $error = false)
	{
		$logString = PHP_EOL . "[" . date('Y.m.d H:i:s') . "]" . "Изменений: {$this->totalChanges}" . PHP_EOL;

		foreach ($data as $type => $guids) {
			$logString .= $type . ": " . PHP_EOL;

			foreach ($guids as $guid) {
				$logString .= $guid . "; ";
			}

			$logString .= PHP_EOL;
		}

		$logString .= PHP_EOL;

		if (/*filesize($file) > 100000 && */!$error) {
			@file_put_contents($file, $logString);
		} else {
			@file_put_contents($file, $logString, FILE_APPEND);
		}
	}

	/**
	 * - Ничего не обновляется
	 * - Добавление нового товара(вместе со всеми связями)
	 *
	 * @param $data
	 * @throws \ReflectionException
	 */
	protected function updateProduct($data)
	{
		foreach ($data as $guid) {
			$apiProduct = ProductRepository::get()
				->setSource($this->source)
				->findByProductGuid($guid)
				->one();

			if ($apiProduct) {
				$dbProduct = LexemaProduct::findByGuid($guid);

				if ($dbProduct) {
					// TODO
					// нельзя обновлять
					// возможно тут проверка на скидки, на сертификаты и может еще что-нибудь
					//continue;
					//$dbProduct->loadFromRemote($apiProduct);
					//$dbProduct->save(false);
					$this->failed['product'][] = $guid;
				} else {
					$dbProduct = new LexemaProduct($apiProduct);
					$dbProduct->save();
					$this->successful['product'][] = $guid;
				}
			}
		}
	}

	/**
	 * - Обновление атрибутов
	 * - Обновление вкл/выкл спец цены
	 *
	 * @param $data
	 * @throws \ReflectionException
	 */
	protected function updateClient($data)
	{
		foreach ($data as $guid) {
			$apiClient = ClientRepository::get()
				->setSource($this->source)
				->find(['ContractorGlobalId' => $guid])
				->one();

			if ($apiClient) {
				$dbClient = LexemaClient::findByGuid($guid);

				if ($dbClient) {
					$dbClient->loadFromRemote($apiClient);
					$dbClient->save(false);
				} else {
					$dbClient = new LexemaClient($apiClient);
					$dbClient->save(false);
				}

				$this->successful['client'][] = $guid;
			}
		}
	}

	/**
	 * - Обновляются текущие цены
	 *
	 * @param $data
	 * @throws \ReflectionException
	 */
	protected function updatePrice($data)
	{
		foreach ($data as $guid) {
			$prices = PriceRepository::get()
				->setSource($this->source)
				->find(['ProductGlobalId' => $guid])
				->all();

			if ($prices) {
				$dbProduct = LexemaProduct::findByGuid($guid);

				if (!$dbProduct) {
					$this->failed['price'][] = $guid;
					continue;
				}

				$dbProduct->setDbPrice();
				echo "Обновление: Цены товара " . $dbProduct->title . PHP_EOL;
				$this->successful['price'][] = $guid;
			}
		}
	}

	/**
	 * - Получение документа заказа
	 *
	 * @param $data
	 */
	protected function updateOrder($data)
	{
		foreach ($data as $vcode) {
			/** @var LexemaApiOrder | Order $dbOrder */
			$dbOrder = LexemaApiOrder::findByVcode($vcode);

			if ($dbOrder) {
				$dbOrder->getDocumentFiles();
				$this->successful['order'][] = $vcode;
			} else {
				$this->failed['order'][] = $vcode;
			}
		}
	}

	/**
	 * - Установка товара с пришедшим guid
	 * 	 в категорию скидок
	 * - Если товара нет в БД - пропускаем
	 *
	 * @param $data
	 * @throws \ReflectionException
	 * @throws \yii\base\Exception
	 */
	protected function updateSale($data)
	{
		$saleCategory = Setting::get('PRODUCT.LIST.DISCOUNT.CATEGORY.ID');

		foreach ($data as $guid) {
			$dbProduct = LexemaProduct::findByGuid($guid);

			if (!$dbProduct) {
				$this->failed['sale'][] = $guid;
				continue;
			}

			$isInSales = ProductToCategory::find()
				->where(['productId' => $dbProduct->id])
				->andWhere(['categoryId' => $saleCategory->id])
				->one();

			if (!$isInSales) {
				$dbProduct->link('categories', $saleCategory);
			}
			$this->successful['sale'][] = $guid;
		}
	}

	// TODO непонятно чей гуид приходит
	/**
	 *
	 * @param $data
	 */
	protected function updateAnalogue($data)
	{
		return;
	}

	/**
	 * - Здесь не берем
	 *
	 * @param $data
	 */
	protected function updateAct($data)
	{
		return;
	}

	/**
	 * - Не трогаем
	 *
	 * @param $data
	 */
	protected function updateCategory($data)
	{
		return;
	}

}