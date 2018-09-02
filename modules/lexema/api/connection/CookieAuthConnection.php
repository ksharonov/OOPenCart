<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 28.05.2018
 * Time: 10:53
 */

namespace app\modules\lexema\api\connection;


use app\modules\lexema\api\base\IConnection;
use yii\base\Component;

class CookieAuthConnection extends Component implements IConnection
{
	/**
	 * Инстанс
	 * @var self $instance
	 */
	public static $instance;

	/**
	 * curl
	 * @var resource $curl
	 */
	protected $curl;

	/**
	 * Конфигурация
	 * @var array $config
	 */
	protected $config;

	/**
	 * Получение инстанса
	 * @return CookieAuthConnection
	 */
	public static function get()
	{
		if (self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->config = include(\Yii::getAlias('@app/modules/lexema/api/config/connection.php'));
		//$this->curl = curl_init();
	}

	/**
	 * Настройка curl
	 * @param $url
	 * @param null $post
	 */
	public function setCurlOptions($url, $post = null)
	{
		$this->curl = curl_init();

		if ($post) {
			curl_setopt($this->curl, CURLOPT_POST, true);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($post));
		}

		$options = [
			CURLOPT_URL 			=> $url,
			CURLOPT_RETURNTRANSFER 	=> true,
			// TODO протестировать без этой опции
			//CURLOPT_COOKIESESSION 	=> true,
			CURLOPT_COOKIEJAR 		=> $this->config['cookie_file'],
			CURLOPT_COOKIEFILE 		=> $this->config['cookie_file'],
			CURLOPT_VERBOSE 		=> 0,
			CURLOPT_USERAGENT 		=> $this->config['user_agent'],
			CURLOPT_FOLLOWLOCATION 	=> true,
			// использовать при дебаге
			CURLOPT_HEADER 			=> false,
		];

		curl_setopt_array($this->curl, $options);
	}

	/**
	 * Конструирование url'a для запроса
	 * @param $params
	 * @return string
	 */
	protected function buildUrl($params)
	{
		$baseUrl = $this->config['api_url'] . 'data/' . $this->config['def_org_id'] . '/';

		if (is_string($params)) {
			$url = $baseUrl . $this->config['type_query'] . '?name=' . $this->config['namespace_esi'] .  $params;
			return $url;
		}

		$urlParams = '';
		if (isset($params['params']) && $params['params'] != ['']) {
			foreach ($params['params'] as $param => $value) {
				if (!is_null($value) && $value) {
					$urlParams .= '&' . $param . '=' . $value;
				}
			}
		}

		$url = $baseUrl . ($params['type'] ?? $this->config['type_query']) . '?name=' .
			($params['namespace'] ?? $this->config['namespace_esi']) . $params['model'] . $urlParams;

		return $url;
	}

	/**
	 * Авторизация(получение куков)
	 * @param $result
	 */
	protected function getAuth($result = null)
	{
		if (!$result) {
			$url = $this->config['api_cookie_auth_url'];
			$this->setCurlOptions($url);
			$result = curl_exec($this->curl);
		}

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($result);
		libxml_clear_errors();

		$viewState = $doc->getElementById('__VIEWSTATE')
			->getAttribute('value');

		$viewStateGenerator = $doc->getElementById('__VIEWSTATEGENERATOR')
			->getAttribute('value');

		$eventValidation = $doc->getElementById('__EVENTVALIDATION')
			->getAttribute('value');

		$postData = [
			'__VIEWSTATE' => $viewState,
			'__VIEWSTATEGENERATOR' => $viewStateGenerator,
			'__EVENTVALIDATION' => $eventValidation,
			'ctl00$Content$Username' => $this->config['api_username'],
			'ctl00$Content$Password' => $this->config['api_password'],
			'ctl00$Content$UserLogin' => 'Войти',
		];

		$this->setCurlOptions($this->config['api_cookie_auth_url'], $postData);

		curl_exec($this->curl);
	}

	/**
	 * Запрос на получение данных
	 * @param $params
	 * @return mixed
	 */
	public function request($params)
	{
		$url = $this->buildUrl($params);
		$this->setCurlOptions($url);

		$result = curl_exec($this->curl);

		$res = strpos($result, '<!DOCTYPE html>');

		if ($res !== false) {
			$this->getAuth($result);
			$this->setCurlOptions($url);

			$result = curl_exec($this->curl);
		}

		return $result;
	}

	/**
	 * Запрос на отправку данных(заказ)
	 * @param $params
	 * @param $data
	 * @param $method
	 * @return bool
	 * @throws \Exception
	 */
	public function send( $params, $data, $method = null )
	{
		$httpOkResult = $this->prepareSend($params, $data, $method);

		if ($httpOkResult === true) {
			return true;
		} else {
			$this->getAuth();
			$result = $this->prepareSend($params, $data, $method);
			if ($result === true) {
				return true;
			} else {
				throw new \Exception('Ошибка отправки заказа. Код: ' . $result[0]);
			}
		}
	}

	protected function prepareSend($params, $data, $method = null)
	{
		$url = $this->buildUrl($params);
		$content = json_encode($data);
		$headers = [
			'Content-type: json; charset=UTF-8',
			"Content-length: " . strlen($content),
		];

		$this->curl = curl_init();

		//$this->setCurlOptions($url, $content);
		$options = [
			CURLOPT_URL 			=> $url,
			CURLOPT_RETURNTRANSFER 	=> true,
			CURLOPT_COOKIEJAR 		=> $this->config['cookie_file'],
			CURLOPT_COOKIEFILE 		=> $this->config['cookie_file'],
			CURLOPT_VERBOSE 		=> 0,
			CURLOPT_USERAGENT 		=> $this->config['user_agent'],
			CURLOPT_FOLLOWLOCATION 	=> false,
			//CURLOPT_POST 			=> true,
			CURLOPT_POSTFIELDS 		=> $content,
			CURLOPT_HTTPHEADER 		=> $headers,
			// использовать при дебаге
			CURLOPT_HEADER 			=> true,
		];
		curl_setopt_array($this->curl, $options);

		if ($method === null) {
			curl_setopt($this->curl, CURLOPT_POST, true);
		} else {
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		}

		$output = curl_exec($this->curl);

		$httpOkResult = strpos($output, 'HTTP/1.1 200');
		$array = explode(PHP_EOL, $output);

		if ($httpOkResult === false) {
			return $array;
		} else {
			return true;
		}
	}
}