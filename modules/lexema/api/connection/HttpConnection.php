<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 07.05.2018
 * Time: 15:04
 */

namespace app\modules\lexema\api\connection;


use app\modules\lexema\api\base\IConnection;
use yii\base\Component;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\httpclient\Response;

class HttpConnection extends Component implements IConnection
{
	/** Configuration */

	const API_URL 			= "http://77.79.133.189:16566/api/v1.0/";
//	const API_AUTH_URL 		= "http://77.79.133.189:16566/api/v2.0/authentication/oauth";
	const API_AUTH_URL 		= "http://77.79.133.189:16566/api/v1.0/OAuth/Token";
	const API_CLIENT_ID 	= "1";
	const API_SECRET_KEY 	= "9f9739c8-446a-465c-b46d-b82a95f1c97e";
//	const API_SECRET_KEY 	= "464cf08e-3dc3-41fd-9476-c94b57934672";
	const API_USERNAME 		= "SiteTest2";
	const API_PASSWORD 		= "Detiafriki123";
	
	const TYPE_QUERY 		= "query";
	const TYPE_MODEL 		= "model";
	
	const NAMESPACE_ESI 	= "ESI.";
	const NAMESPACE_RETAIL 	= "Retail.";

	const COOKIE_FILE 		= "./cookie.txt";

	/**	------------------- */

	/**
	 * @var resource $curl
	 */
	private $curl = null;

	/** Токен
	 * @var object $token
	 */
	protected $_token = null;

	/** Инстанс
	 * @var self
	 */
	public static $instance = null;

	/** Сообщение об ошибке
	 * @var string
	 */
	public $lastError = null;

	/** Получение инстанса
	 * @return HttpConnection
	 */
	public static function get()
	{
		if (self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Настройка curl для получения Access Token
	 * @param $url
	 * @param $post
	 * @return mixed
	 * @deprecated
	 */
	private function setupCurl($url, $post)
	{
		$options = [
			CURLOPT_URL 			=> $url,
			CURLOPT_RETURNTRANSFER 	=> true,
			CURLOPT_COOKIESESSION 	=> true,
			CURLOPT_COOKIEJAR 		=> self::COOKIE_FILE,
			CURLOPT_COOKIEFILE 		=> self::COOKIE_FILE,
			CURLOPT_VERBOSE 		=> 1,
			CURLOPT_POST 			=> 1,
			CURLOPT_POSTFIELDS 		=> http_build_query($post),
			CURLOPT_HTTPHEADER 		=> [
				'Content-type: application/json',
			],
			CURLOPT_USERAGENT => 'ESI Import',
		];

		curl_setopt_array($this->curl, $options);
		$result = curl_exec($this->curl);

		return $result;
	}

	/**
	 * @throws \Exception
	 */
	public function getToken()
	{
		$this->_token = Token::get();
		
		return $this->_token;
	}


	/**
	 * Конструирование url'a для запроса
	 * @param $params
	 * @return string
	 */
	protected function buildUrl($params)
	{
		$baseUrl = self::API_URL . 'data/' . $this->token->defaultOrganization . '/';

		if (is_string($params)) {
			$url = $baseUrl . self::TYPE_QUERY . '?name=' . self::NAMESPACE_ESI .  $params;
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

		$url = $baseUrl . ($params['type'] ?? self::TYPE_QUERY) . '?name=' .
			($params['namespace'] ?? self::NAMESPACE_ESI) . $params['model'] . $urlParams;

		return $url;
	}

	/**
	 * @param $params
	 * @return string
	 */
	public function request($params)
	{
		$url = $this->buildUrl($params);

		$client = new Client([
			'baseUrl' => $url,
			'responseConfig' => [
				'format' => Client::FORMAT_JSON,
			]
		]);

		/** @var Response $response */
		$response = $client->createRequest()
			->setMethod('GET')
			->addHeaders(['Authorization' => "Bearer {$this->token->access_token}"])
			->send();

		//$json = json_decode($response->content, true);

		return $response->content;
	}

	/**
	 * @param $params
	 * @param $content
	 * @param string $charset
	 * @return mixed
	 * @throws \Exception
	 */
	public function send( $params, $content, $charset = 'UTF-8')
	{
		$url = $this->buildUrl($params);

		$client = new Client([
			'baseUrl' => $url,
			'responseConfig' => [
				'format' => Client::FORMAT_JSON,
			],
			'requestConfig' => [
				'format' => Client::FORMAT_JSON,
			]
		]);

		$jsonData = json_encode($content);

		$request = $client->createRequest()
			->setMethod('POST')
			->addHeaders([
				'Authorization' => "Bearer {$this->token->access_token}",
				'Content-type' => "application/json;charset={$charset}",
				"Content-length" => mb_strlen($jsonData),
			])
			->setData($jsonData);

		/** @var Response $response */
		$response = $request->send();

		$result = json_decode($response->content, true);

		if (isset($result['Message']) || isset($result['exception'])) {
			throw new \Exception('Ошибка в запросе. Возможно не валидный xml.');
		}

		return $result;
	}
}