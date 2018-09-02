<?php

namespace app\modules\lexema\models;

class LexemaConnect
{
    const API_URL         = "http://77.79.133.189:16566/api/v1.0";
    const API_CLIENT_ID   = "1";
    //const API_SECRET_KEY  = "9F9739C8-446A-465C-B46D-B82A95F1C97E";
    const API_SECRET_KEY  = "9f9739c8-446a-465c-b46d-b82a95f1c97e";
    //const API_SECRET_KEY  = "464cf08e-3dc3-41fd-9476-c94b57934672";
    const LEXEMA_USERNAME = "SiteTest2";
    const LEXEMA_PASSWORD = "Detiafriki123";
    const COOCKIE_FILE = "./cookie.txt";

    public $errorMsg = null;
    public $respone = null;

    public $token      = null;
    private $lastError = "";
    private $curl      = null;
    private $orgid     = 0;

    const LEXEMA_NAMESPACE = "ESI";
    const RETAIL_NAMESPACE = "Retail";


    public function __construct()
    {
        $this->curl = curl_init();
    }
    /*

      Список товара :     170 ESI_GetProduct
      параметры: ProductglobalId - выборка конкретной номенклатуры
      FolderglobalId - выборка по папке товара
      Список единиц измерения: 171 ESI_Edizm  (Без параметров в принципе, можно считать статическим справочником)
      Аналоги товара 172 ESI_Analogue (Параметр ProductGlobalId если null то выведет всё)
      Сопутствующие товары 173 ESI_TovarSoput (Параметр ProductGlobalId если null то выведет всё)
      Справочник типов цен: 174 ESI_TypePriceZena (статический справчник)
      Прайс 175 ESI_Price (тут работает функция на стороне скула работает порядка 5-10 сек Так что каждую минуту думаю считать не стоит)

      Склады 176 (ESI_GetChangeStorage): там 2 параметра. Mode -0 вернёт всё, 1- только где изменилось кол-во

      ESI_GetSemaphorChanges (id 181): новый запрос в лексеме возвращает объекты (пока только Товары, Каталоги, Аналоги, Сопутствующие),
                                        которые были изменены за последние N минут, N - параметр запроса
      182 ESI_GetStorage - склады(названия и гуиды)
      183 ESI_GetShop - магазины(адреса, названия и гуиды)
      184 ESI_GetLoggin - Такс. пользователи, которых зарегистрировали в качестве админов в ЛК.
                           там есть параметр Status если передаёш Null вернёт весь список, если надо только новых, передаём код = 49446

      185 ESI_GetContractor - список контрагентов (клиенты у нас), есть необязательный параметр Contractor, возвращает только тех, у кого есть логин в ЛК

      ESI_GetSemaphorChanges 181
Кароч, он выводит список моделей и коды аналитик, по которым были изменения за последние DepthDate минут. (DepthDate - параметр задающийся в минутах)
вызови его с DepthDate = 1000 получиш список объектов, которые изменились за последние грубо говоря 16 часов
     *      */


    /* (код: 19892, наименование: EZK_Esi))
     * Немного дам описания какие поля надо заполнить.
      1.Vcode (Primary Key) Он либо должен генерится автоматом, либо вот так
      "Генерация первичного ключа для документа: api/v1.0/data/{orgid}/genvcode/{modelName}"
      2.Tdoc - сюда пишете статическое выражение "EZK"
      3.Rdate - текущая дата. Желательно без времени
      Также заполните поля для теста
      wuser,wdate,cuser,cdate
      Ссылка на описание в вики */

    private function request($url, $post = null, $charset='UTF-8')
    {
        if ($this->token != null) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$this->token->access_token}"]);
        }

        curl_setopt($this->curl, CURLOPT_URL, self::API_URL.$url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        $tmpfname = self::COOCKIE_FILE;
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $tmpfname);
        curl_setopt($this->curl, CURLOPT_VERBOSE, 0); // 0 - отключает вывод дебаг сообщений курла

        //$verbose = fopen('D:\OpenServer\domains\220volt2/curllog.txt', 'w+');
        //curl_setopt($this->curl, CURLOPT_STDERR, $verbose);

        curl_setopt($this->curl, CURLOPT_POST, $post !== null);
        if ($post) {

            if ($this->token == null) {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($post));
            } else {

                $json_data = json_encode($post);

                try {
                    $result = file_get_contents(self::API_URL.$url, null,
                    stream_context_create(array(
                    'http' => array(
                        'ignore_errors' => true,
                        'protocol_version' => 1.1,
                        'user_agent' => 'ESI Import',
                        'method' => 'POST',
                        'header' => [
                            "Authorization: Bearer {$this->token->access_token}",
                            "Content-type: json; charset={$charset}",
                            "Content-length: ".mb_strlen($json_data),
                        ],
                        //'header' => "Content-type: application/json; charset={$charset}\r\nAuthorization: Bearer {$this->token->access_token}\r\n",
                        //"Connection: close\r\n".
                        //"Content-length: ".mb_strlen($json_data)."\r\n",
                        'content' => $json_data,
                    ),
                    )));
                } catch (Exception $e) {
                    $result = 'Выброшено исключение: '.  $e->getMessage(). "\n";
                }
                return $result;
            }
        }

        $data = curl_exec($this->curl);

        //print_r($post);
        //print_r($data);exit();

        return $data;
    }

	/**
	 * @param $params
	 * @return bool
	 */
    public function sendOrder($params)
    {
        error_reporting(E_ALL);

        $charset = $_GET["charset"] ?? "UTF-8";

		$lexResult = $this->request("/data/{$this->token->defaultOrganization}/query?name=ESI.ESI_GetReestrEZK", $params, $charset);

        $x = [
            "respone"=>$lexResult,
            "request"=>$params,
        ];

        $log = \Yii::getAlias("@app/runtime/logs/") . "order.log";

        $result = json_decode($lexResult, true);

        if ($result && array_key_exists('exception', $result)) {
            $this->errorMsg = $result['exception']['Message'];
            $fileData = date("Y-m-d H:i:s") . "\n\r\n\r";
            file_put_contents($log, array($fileData, print_r($x, true)));
            throw new \Exception('Ошибка в запросе.');
        } else if ($result && array_key_exists('error', $result)) {
			$this->errorMsg = $result['error'];
			$fileData = date("Y-m-d H:i:s") . "\n\r\n\r";
			file_put_contents($log, array($fileData, print_r($x, true)));
			throw new \Exception('Ошибка в запросе.');
			return false;
		} else {
            $this->response = $result;
            $fileData = date("Y-m-d H:i:s") . "\n\r\n\r";
            file_put_contents($log, array($fileData, print_r($x, true)));
            return true;
        }


    }



    /** Запрос с использованием JsonMapper для любого типа запроса
     *  Аргументы: имя запроса в Лексеме, класс JsonMapper
     *  Возвращает данные по указанному имени запроса в виде массива объектов JsonMapper
     * @param $name
     * @param $className
     * @return array
	 * @deprecated
     */
    public function query($name, $className): array
    {
        $json       = $this->request("/data/{$this->token->defaultOrganization}/query?name=".self::LEXEMA_NAMESPACE.'.'.$name);
        $jsonObject = json_decode($json);

        $mapper                          = new \JsonMapper();
        $mapper->bExceptionOnMissingData = false;
        $mapper->bStrictNullTypes        = false;

        $result = $mapper->mapArray(
            $jsonObject, array(), $className
        );

        return $result;
    }

	/**
	 * @param $name
	 * @param $post
	 * @return mixed
	 * @deprecated
	 */
    public function queryPost($name, $post) {
        $json = $this->request("/data/{$this->token->defaultOrganization}/query?name=".self::LEXEMA_NAMESPACE.".".$name, $post);

        return json_decode($json, true);
    }

    /** Запрос без использования JsonMapper для продуктов (выдает ошибку, если в json'е есть null)
     *  Аргумент - категория продукта
     *  Возвращает все продукты из указанной категории в виде обычного массива
     * @param $categoryGlobalId
     * @return array
	 * @deprecated
     */
    public function queryProducts($categoryGlobalId): array
    {
        /*
          ProductglobalId - судя по всему guid категории продукта  ---  parentglobalId в json'e
          FolderglobalId - guid самого продукта                    ---  globalId       в json'e

         */
        $json = $this->request("/data/{$this->token->defaultOrganization}/query?name=ESI.ESI_GetProduct&ProductglobalId=$categoryGlobalId");

        return json_decode($json, true);
    }

    /** Запрос без использования JsonMapper для файлов
     *  Аргумент - guid продукта
     *  Возвращает все файлы, имеющиеся у указанного продукта(закодированные в base64) в виде обычного массива
     * @param $productGlobalId
     * @return array
	 * @deprecated
     */
    public function queryFiles($productGlobalId) :array
    {
        $json = $this->request("/data/{$this->token->defaultOrganization}/query?name=ESI.ESI_GetProductImages&GlobalIdMaterial=$productGlobalId");

        return json_decode($json, true);
    }

    /** Запрос без использования JsonMapper для любого типа запроса
     *  Аргумент - имя запроса в Лексеме
     *  Возвращает данные по имени запроса в виде обычного массива
     * @param $name
     * @return array
     */
    public function queryArray($name, $namespace = self::LEXEMA_NAMESPACE): array
    {
        $result = $this->request("/data/{$this->token->defaultOrganization}/query?name=". $namespace .".".$name);
        $json = json_decode($result, true);

        return $json;
    }

    public function modelArray($name, $namespace = self::RETAIL_NAMESPACE): array
	{
		$result = $this->request("/data/{$this->token->defaultOrganization}/model?name=". $namespace .".".$name);
		$json = json_decode($result, true);

		return $json;
	}

	/**
	 * @param $name
	 * @return array
	 * @deprecated
	 */
    public function queryObject($name): array
    {
        $json = $this->request("/data/{$this->token->defaultOrganization}/query?name=".self::LEXEMA_NAMESPACE.".".$name);

        return json_decode($json);
    }

    /** Получение токена
     * @return bool
     */
    public function getToken()
    {
        // это для обновления токена
        $this->token = null;

        $data = [
            "grant_type" => "password",
            "client_id" => self::API_CLIENT_ID,
            "client_secret" => self::API_SECRET_KEY,
            "userName" => self::LEXEMA_USERNAME,
            "password" => self::LEXEMA_PASSWORD,
        ];

        $data = $this->request("/OAuth/Token", $data);

        //dump($data);exit;

        $dataObj = json_decode($data);

        if (is_object($dataObj) && isset($dataObj->access_token)) {
            $this->token = $dataObj;

            return true;
        } else {
            $this->lastError = is_object($dataObj) ? "Нет акцессТокена в ответе" : "Не корректный ответ JSON";
            return false;
        }
    }

    /** Обновление токена
     * @return bool
     */
    public function refreshToken()
    {
        // todo НЕРАБОТАЕТ (возвращает {"error":"invalid_client"})
        $data = [
            "grant_type" => "refresh_token",
            "client_id" => self::API_CLIENT_ID,
            "client_secret" => self::API_SECRET_KEY,
            "refresh_token" => $this->token->refresh_token,
        ];

        $this->token = null;

        $data = $this->request("/OAuth/Token", $data);
        $dataObj = json_decode($data);

        if (is_object($dataObj) && isset($dataObj->refresh_token)) {
            $this->token = $dataObj;
            return true;
        } else {
            $this->lastError = is_object($dataObj) ? "Нет акцессТокена в ответе" : "Не корректный ответ JSON";
            return false;
        }
    }
}