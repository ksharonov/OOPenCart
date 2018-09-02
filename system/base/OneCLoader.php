<?php
/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 15:38
 */

namespace app\system\base;

use app\models\db\Setting;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\httpclient\Client;


class OneCLoader extends BaseObject
{
    public $file;
    public $count;
    public $documentOneC = null;
    public $typeOneC = null;
    public $source = null;
    public $data = [];

    public function __construct(array $config = [])
    {
        $this->count = 0;
        $this->prepareFile();
        parent::__construct($config);

    }

    public function prepareFile()
    {
        //$this->file = simplexml_load_file($this->path);

    }

    public function load()
    {
        $one_import_url = Setting::get('ONEC.IMPORT.URL');
        $name_base = Setting::get('ONEC.NAME.BASE');
        $auth_base = base64_encode(Setting::get('ONEC.AUTH'));
        $server_one_c = 'http://' . $one_import_url . '/' . $name_base . '/hs/';
        $client = new \yii\httpclient\Client(['baseUrl' => $server_one_c . $this->source]);

        $request = $client->createRequest()
            ->setHeaders(['content-type' => 'application/json'])
            ->addHeaders(['authorization' => 'Basic ' . $auth_base]);
        //->addHeaders(['authorization' => 'Basic 0JDQtNC80LjQvdC40YHRgtGA0LDRgtC+0YA6']);
        try {
            $response = $request->send();
            $obj = \yii\helpers\Json::decode(mb_convert_encoding($response->content, "UTF-8", "UTF-8"));
        } catch (\Exception $e) {
            $obj = false;
        }


        return $obj;
    }

    public function createObjOneC()
    {
        //print_r($this->data);
        $one_import_url = Setting::get('ONEC.IMPORT.URL');
        $name_base = Setting::get('ONEC.NAME.BASE');
        $auth_base = base64_encode(Setting::get('ONEC.AUTH'));
//        dump($this->documentOneC);
        //$doc = mb_convert_encoding($this->documentOneC, "windows-1251");
        $doc = $this->documentOneC; //Павел. 10.08.18. По просьбе Эльшата
        $client = new \yii\httpclient\Client();
        //echo 'http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/'. $this->typeOneC .'_' . $doc . '?$format=json';
        //echo 'http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/'. $this->typeOneC .'_' . $this->documentOneC . '?$format=json';
        //print_r(json_encode($this->data));
//        dump('http://' . $one_import_url . '/' . $name_base . '/odata/standard.odata/' . $this->typeOneC . '_' . $doc . '?$format=json');
        $response = $client->createRequest()
            ->setMethod('post')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://' . $one_import_url . '/' . $name_base . '/odata/standard.odata/' . urlencode($this->typeOneC . '_' . $doc) . '?$format=json')
            //->setUrl('http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/'. $this->typeOneC .'_' . $this->documentOneC . '?$format=json')
            //->setUrl('http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/Document_' . $this->documentOneC . '?$format=json')
            ->setHeaders([
                'content-type' => 'application/json',
                'authorization' => 'Basic ' . $auth_base //усатов infobase2
                //'authorization' => 'Basic 0JDQtNC80LjQvdC40YHRgtGA0LDRgtC+0YA6' //Администратор infobase1
            ])
            ->setData($this->data)
            ->send();
        if ($response->isOk) {
            //$newUserId = $response->data['id'];
            //dump($response);
            return $response;
        } else {
            dump($response);
        }
        //echo $this->documentOneC;
        //dump($response);
    }

    public function updateObjOneC()
    {
        //print_r($this->data);
        $one_import_url = Setting::get('ONEC.IMPORT.URL');
        $name_base = Setting::get('ONEC.NAME.BASE');
        $auth_base = base64_encode(Setting::get('ONEC.AUTH'));
        
        //$doc = mb_convert_encoding($this->documentOneC, "windows-1251");
        $doc = $this->documentOneC;
        
        
        $client = new \yii\httpclient\Client();
        $response = $client->createRequest()
            ->setMethod('patch')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl('http://' . $one_import_url . '/' . $name_base . '/odata/standard.odata/' . urlencode($this->typeOneC . '_' . $doc) . '?$format=json')
            //->setUrl('http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/Document_' . $doc . '?$format=json')
            ->setHeaders([
                'content-type' => 'application/json',
                'authorization' => 'Basic ' . $auth_base //усатов infobase2
                //'authorization' => 'Basic 0JDQtNC80LjQvdC40YHRgtGA0LDRgtC+0YA6' //Администратор infobase1
            ])
            ->setData($this->data)
            ->send();
        if ($response->isOk) {
            //$newUserId = $response->data['id'];
            echo "**********************************\n";
            dump($response);
        } else {
            dump($response);
        }
        //echo $this->documentOneC;
        //dump($response);
    }

    public function getObjOneC()
    {
        $one_import_url = Setting::get('ONEC.IMPORT.URL');
        $name_base = Setting::get('ONEC.NAME.BASE');
        $auth_base = base64_encode(Setting::get('ONEC.AUTH'));
        //$doc = mb_convert_encoding($this->documentOneC, "windows-1251");
        $doc = $this->documentOneC;
        $client = new \yii\httpclient\Client();
        //echo 'http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/'. $this->typeOneC .'_' . $doc . '?$format=json';
        //echo 'http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/'. $this->typeOneC .'_' . $this->documentOneC . '?$format=json';
        //print_r(json_encode($this->data));
        
        $url = 'http://' . $one_import_url . '/' . $name_base . '/odata/standard.odata/' . urlencode($this->typeOneC . '_' . $doc) . '?$format=json';
        
        //$url = urlencode($url);
        
        echo $url;
        $response = $client->createRequest()
            ->setMethod('get')
            ->setFormat(Client::FORMAT_JSON)
            ->setUrl($url)
            //->setUrl('http://'.$one_import_url.'/'.$name_base.'/odata/standard.odata/'. $this->typeOneC .'_' . $this->documentOneC . '?$format=json')
            ->setHeaders([
                'content-type' => 'application/json',
                'authorization' => 'Basic ' . $auth_base //усатов infobase2
            ])
            ->setData($this->data)
            ->send();
        
        echo "1C response:\n";
        print_r($response);
        
        if ($response->isOk) {
            //dump($response);
            return $response;
        } else {
            //dump($response);
            return false;
        }
        //echo $this->documentOneC;
        //dump($response);
    }

}