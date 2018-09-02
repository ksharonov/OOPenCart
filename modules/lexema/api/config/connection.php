<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 23.05.2018
 * Time: 17:52
 */

return [
	'api_url' 				=> "http://77.79.133.189:16566/api/v1.0/",
	'api_auth_url' 			=> "http://77.79.133.189:16566/api/v1.0/OAuth/Token",

	'api_cookie_auth_url' 	=> 'http://77.79.133.189:16566/Account/Login.aspx',
	'user_agent' 			=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',

	'api_client_id' 		=> "1",
	'api_secret_key' 		=> "9f9739c8-446a-465c-b46d-b82a95f1c97e",
	'api_username' 			=> "SiteTest2",
	'api_password' 			=> "Detiafriki123",

	'namespace_esi' 		=> "ESI.",
	'namespace_retail' 		=> "Retail.",

	'type_query' 			=> 'query',
	'type_model' 			=> 'model',

	'cookie_file' 			=> \Yii::getAlias("@app/runtime/lexema_cookie.txt"),

	'def_org_id' 			=> 4
];