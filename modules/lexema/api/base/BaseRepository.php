<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 16.05.2018
 * Time: 12:46
 */

namespace app\modules\lexema\api\base;

use app\modules\lexema\api\connection\CookieAuthConnection;
use app\modules\lexema\api\connection\HttpConnection;
use app\modules\lexema\api\base\IConnection;
use app\modules\lexema\models\LexemaConnect;
use yii\base\BaseObject;
use yii\db\Connection;

abstract class BaseRepository extends BaseObject
{
	/**
	 * Массив инстансов дочерних классов
	 * @var BaseRepository[]
	 */
	protected static $instances = [];

	/**
	 * Настройка url. Для каждого класса - свой.
	 * @var array|string
	 */
	protected $url;

	/**
	 *  Источник получения данных.
	 * @var IConnection
	 */
	protected $source;

	/**
	 * Массив с обработанными данными
	 * @var array
	 */
	protected $data;

	/**
	 * Данные поиска
	 * @var array
	 */
	protected $foundData;

	/**
	 * Инстанс
	 * @return BaseRepository|static
	 */
	public static function get()
	{
		$class = get_called_class();

		if(!isset(self::$instances[$class])) {
			self::$instances[$class] = new $class;
		}

		return self::$instances[$class];
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->source = CookieAuthConnection::get();
		parent::init();
	}

	/**
	 * Добавил пока просто так
	 * Можно установить класс, с интерфейсом IConnection
	 * @param IConnection $connection
	 * @return BaseRepository
	 */
	public function setSource(IConnection $connection)
	{
		$this->source = $connection;

		return $this;
	}

	/**
	 * Поиск по параметрам
	 * доку писать лень
	 * @param null $params
	 * @return BaseRepository
	 * @throws \Exception
	 */
	public function find($params = null)
	{
		if (!$this->data) {
			if (!$this->url) {
				throw new \Exception('Не задан URL В классе: ' . self::className());
			}

			$data = $this->source->request($this->url);
			$this->data['index'] = json_decode($data, true);
		}

		// без аргумента - обычный массив с числовыми индексами
		if (is_null($params)) {
			$this->foundData = 'index';
			return $this;
		}

		// целочисленный аргумент - поиск по числовому индексу
		if (is_numeric($params)) {
			$this->foundData = $params;//&$this->data['index'][$params] ?? null;
			return $this;
		}

		// стринговый аргумент - возвращает весь массив, индексированный аргументом
		if (is_string($params)) {
			$this->addIndex($params);
			$this->foundData = &$this->data[$params] ?? null;

			return $this;
		}

		if (is_array($params)) {
			$key = key($params);
			$value = $params[$key];

			// целочисленный аргумент - поиск по числовому индексу (дублирование)
			if (is_numeric($key) && is_numeric($value)) {
				$this->foundData = &$this->data['index'][$value] ?? null;

				return $this;
			}

			// стринговый аргумент - возвращает весь массив, индексированный аргументом
			if (is_numeric($key) && is_string($value)) {
				$this->addIndex($value);
				$this->foundData = &$this->data[$value] ?? null;

				return $this;
			}

			$this->foundData = $this->search($key, $value);

			return $this;
		}
	}

	/**
	 * Выбор одного найденного элемента
	 * @return mixed|null
	 */
	public function one()
	{
		if (is_numeric($this->foundData)) {
			return $this->data['index'][$this->foundData] ?? null;
		}

		if (is_string($this->foundData)) {
			if (isset($this->data[$this->foundData])) {
				return reset($this->data[$this->foundData]);
			}
		}

		if (is_array($this->foundData)) {
			return reset($this->foundData);
		}
	}

	/**
	 * Выбор всех найденных элементов
	 * @return array|mixed|null
	 */
	public function all()
	{
		if (is_numeric($this->foundData)) {
			return $this->data['index'][$this->foundData] ?? null;
		}

		if (is_string($this->foundData)) {
			if (isset($this->data[$this->foundData])) {
				return $this->data[$this->foundData];
			}
		}

		if (is_array($this->foundData)) {
			return $this->foundData;
		}
	}

	/**
	 * Добавление нового индекса в массив $data
	 * @param $index
	 */
	protected function addIndex($index)
	{
		if (!isset($this->data[$index])) {
			//$indexColumn = array_column($this->data['index'], $index);

			foreach ($this->data['index'] as &$value) {
				/* пока баганое
				$foundKeys = array_keys($indexColumn, $value[$index]);

				if (count($foundKeys) > 1) {
					$this->data[$index][$value[$index]][] = $value;

				} else {
					$this->data[$index][$value[$index]] = $value;
				}
				*/

				$this->data[$index][mb_strtoupper($value[$index])][] = &$value;
			}
		}
	}

	/**
	 * Поиск по параметрам
	 * @param $key
	 * @param $value
	 * @return array|null
	 */
	protected function search($key, $value)
	{
		$this->addIndex($key);

		if (is_array($value)) {
			$found = [];
			foreach ($value as &$item) {
				$found[] = $this->data[$key][mb_strtoupper($item)] ?? null;
			}
			return $found;
		}

		return $this->data[$key][mb_strtoupper($value)] ?? null;
	}
}