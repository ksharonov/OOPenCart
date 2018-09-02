<?php

namespace app\modules\backoffice\models;

class Lexema
{
    const API_URL         = "http://77.79.133.189:16566/api/v1.0";
    const API_CLIENT_ID   = "1";
    const API_SECRET_KEY  = "9f9739c8-446a-465c-b46d-b82a95f1c97e";
    const LEXEMA_USERNAME = "SiteTest2";
    const LEXEMA_PASSWORD = "Detiafriki123";

    private $token     = "";
    private $lastError = "";

    /*

    Список товара :     170 ESI_GetProduct
    параметры: ProductglobalId - выборка конкретной номенклатуры
               FolderglobalId - выборка по папке товара
    Список единиц измерения: 171 ESI_Edizm  (Без параметров в принципе, можно считать статическим справочником)
    Аналоги товара 172 ESI_Analogue (Параметр ProductGlobalId если null то выведет всё)
    Сопутствующие товары 173 ESI_TovarSoput (Параметр ProductGlobalId если null то выведет всё)
    Справочник типов цен: 174 ESI_TypePriceZena (статический справчник)
    Прайс 175 ESI_Price (тут работает функция на стороне скула работает порядка 5-10 сек Так что каждую минуту думаю считать не стоит)
     *      */

    public function __construct()
    {
        ;
    }
    /*
     * Выполнить POST-запрос по адресу /api/v1.0/OAuth/Token
     * grant_type=password
      client_id=1
      client_secret=56E40C16-1ED8-489B-87ED-96433E7CFB12
      userName=login
      password=Password1 */

    public function getToken()
    {
        $ch = curl_init();

        $data = [
            "grant_type" => "password",
            "client_id" => self::API_CLIENT_ID,
            "client_secret" => self::API_SECRET_KEY,
            "userName" => self::LEXEMA_USERNAME,
            "password" => self::LEXEMA_PASSWORD,
        ];

        $tmpfname = 'C:\OpenServer\domains\cookie.txt';
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);

        //print_r($data);

        curl_setopt($ch, CURLOPT_URL, self::API_URL."/OAuth/Token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // required as of PHP 5.6.0
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $data = curl_exec($ch);

        $dataObj = json_decode($data);
        print_r($dataObj);

        if (is_object($dataObj) && isset($dataObj->access_token)) {
            $this->token = $dataObj;
            return true;
        } else {
            $this->lastError = is_object($dataObj) ? "Нет акцессТокена в ответе" : "Не корректный ответ JSON";
            return false;
        }

        //var_dump($data);
    }

    private function testModel()
    {
        //api/v1.0/data/{orgid}/model?name={modelName}orgid = 1
        //SGPRegistry
        //file_get_contents(self::API_URL."/data/1/model?name=SGPRegistry");

        //echo $this->token->access_token;
        //exit();

        $ch = curl_init();

        $tmpfname = 'C:\OpenServer\domains\cookie.txt';

        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
            "Authorization: Bearer {$this->token->access_token}",
            //"Accept: application/json",
            //"VCode: 18615"
        ]);

        //$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL."/data/4/model?id=296");
        //curl_setopt($ch, CURLOPT_URL, self::API_URL."/data/4/query?name=ESI.ESI_Edizm");
        //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //var_dump($this->token->access_token);


        $data = curl_exec($ch);

        print_r($data);

        $dataObj = json_decode($data);
        print_r($dataObj);

        //var_dump($data);
    }

    public function test()
    {
        if (!$this->getToken()) {
            $this->lastError = "Ошибка при получении токена";
            return false;
        }

        $this->testModel();

        ///
    }
}
/*Надо попробовать авторизоваться в API
client_id: 1
secret_key: 9f9739c8-446a-465c-b46d-b82a95f1c97e

 * site
site
попробуй
 *  */