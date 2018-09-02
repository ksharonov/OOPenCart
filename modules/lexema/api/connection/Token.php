<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 11.05.2018
 * Time: 13:53
 */

namespace app\modules\lexema\api\connection;


use yii\httpclient\Client;
use yii\httpclient\Response;

class Token
{
	/** -----Token------ */

	public $access_token;
	public $token_type;
	public $expires_in;
	public $refresh_token;
	public $userName;
	public $defaultOrganization;
	public $receiveTime;
	public $timePassed;

	/** ---------------- */

	const POST_DATA = [
		"grant_type" => "password",
		"client_id" => HttpConnection::API_CLIENT_ID,
		"client_secret" => HttpConnection::API_SECRET_KEY,
		"userName" => HttpConnection::API_USERNAME,
		"password" => HttpConnection::API_PASSWORD,
	];

	const REFRESH_DATA = [
		"grant_type" => "refresh_token",
		"client_id" => HttpConnection::API_CLIENT_ID,
		"client_secret" => HttpConnection::API_SECRET_KEY,
		"refresh_token" => null,
	];

	/**
	 * Инстанс токена
	 * @var
	 */
	static $instance;

	/**
	 * Token constructor.
	 * @param $data
	 */
	public function __construct($data)
	{
		$this->loadParams($data);
	}

	/**
	 * Установка свойств
	 * @param $data
	 */
	private function loadParams($data)
	{
		foreach ($data as $property => $value) {
			$this->$property = $value;
		}
	}

	/**
	 * Получение токена
	 * @return Token
	 * @throws \Exception
	 */
	public static function get()
	{
		if (self::$instance === null) {
			$client = new Client([
				'baseUrl' => HttpConnection::API_AUTH_URL,
				'responseConfig' => [
					'format' => Client::FORMAT_JSON,
				]
			]);

			/** @var Response $response */
			$response = $client->createRequest()
				->setMethod('POST')
				->setData(self::POST_DATA)
				->send();
			
			//dump($response->content);exit;

			$json = json_decode($response->content, true);

			if ($json !== null && isset($json['access_token'])) {
				self::$instance = new Token($json);
				self::$instance->receiveTime = time();
			} else {
				throw new \Exception('Ошибка получения токена.');
			}
		}

		self::$instance->timePassed = time() - self::$instance->receiveTime;

		if (self::$instance->timePassed >= (self::$instance->expires_in - 360)) {
			$result = self::$instance->refresh();
			if ($result) {
				$message = date('Y.m.d H:i:s', time()) . ": Token successfully refreshed" . PHP_EOL;
				echo $message;
				file_put_contents(\Yii::getAlias('@app/runtime/logs/token.log'), $message, FILE_APPEND);
			} else {
				throw new \Exception('Ошибка обновления токена');
			}
		}

		return self::$instance;
	}

	/**
	 * Обновление токена
	 * @throws \Exception
	 */
	public function refresh()
	{
		if (!isset($this->refresh_token)) {
			return null;
			throw new \Exception('Ошибка обновления токена: отсутствует ключ обновления');
		}

		$post = self::REFRESH_DATA;
		$post['refresh_token'] = $this->refresh_token;

		$client = new Client([
			'baseUrl' => HttpConnection::API_AUTH_URL,
			'responseConfig' => [
				'format' => Client::FORMAT_JSON,
			]
		]);

		/** @var Response $response */
		$response = $client->createRequest()
			->setMethod('POST')
			->setData($post)
			->send();

		$json = json_decode($response->content, true);

		if ($json !== null ?? isset($json['access_token'])) {
			$this->loadParams($json);
			$this->receiveTime = time();
			$this->timePassed = 0;
			return true;
		} else {
			return null;
			throw new \Exception('Ошибка обновления токена.');
		}
	}
}