<?php

namespace app\helpers;

use app\models\db\Cheque;
use app\models\db\Setting;
use yii\db\Exception;
use yii\helpers\Json;
use yii\httpclient\Client;
use app\models\db\Order;

class ChequeHelper
{
    /**
     * Временно
     * @var Order
     */
    public $order;

    /**
     * Пароль для авторизации
     */
    const PASSWORD = 28;

    /**
     * Тип шрифта
     */
    const FONT = 1;

    /**
     * Печать чека
     */
    const FULL_RESPONSE_MODE = true;

    /**
     * @param Order $order
     * @return bool
     */
    public function printOrder($order)
    {
        if (!$order || !($order instanceof Order)) {
            return false;
        }

        $this->order = $order ?? null;

        $clientId = 0;//\Yii::$app->client->get()->id ??
        $phoneOrEmail = $order->user->email ?? $order->user->phone ?? 'Информация отсутствует';

        $lines = [];
        $count = 0;

        foreach ($order->content as $content) {
            $count++;

            /**
             * Временно
             */
//            if ($content->priceValue > 100) {
//                return;
//            }

            $lines[] = [
                "Qty" => $content->count * 1000,
                "Price" => $content->priceValue * 100,
                "PayAttribute" => 4,
                "TaxId" => 1,
                "Description" => $content->title
            ];
        }
//        dump($lines);
//        return;
        $response = $this->request('Complex', [
            "Device" => "auto",
            "ClientId" => (string)$clientId,
            "Lines" => $lines,
            "Cash" => 0, //Наличные
            "NonCash" => [0, $order->finalSum * 100, 0],
            "TaxMode" => 1,
            "PhoneOrEmail" => $phoneOrEmail,
            "Place" => Setting::get('SITE.URL'),
            "FullResponse" => self::FULL_RESPONSE_MODE
        ]);


        return $response->isOk ?? false;
    }

    public function printString(string $requestId, string $text)
    {
        $this->request('printString', [
            'RequestId' => $requestId,
            'Password' => self::PASSWORD,
            'Font' => self::FONT,
            'Text' => $text
        ]);
    }

    /**
     *
     */
    public function openCloseSession()
    {
        $this->request('CloseTurn');
        $this->request('OpenTurn');
    }

    /**
     * @param $path
     * @param array $data
     * @return \yii\httpclient\Response
     */
    public function request(string $path, array $data = [])
    {
        $data['Password'] = self::PASSWORD;
        $data['Font'] = self::FONT;
        $requestId = $data['RequestId'] = uniqid('document.', true);
        $content = Json::encode($data);
        $url = 'http://' . Setting::get('CASH.MACHINE.IP') . '/fr/api/v2/' . $path;

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);

        $cheque = new Cheque();
        $cheque->requestId = $requestId;
        $cheque->params = $content;
        $cheque->url = $url;
        $cheque->success = false;
        $cheque->path = $path;
        $cheque->orderId = $this->order->id ?? null;
        $cheque->save();

        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setOptions([
                CURLOPT_FOLLOWLOCATION => true
            ])
            ->setFormat(Client::FORMAT_JSON)
            ->setContent($content);


        $response = $request->send();

        try {
            $responseContent = Json::decode($response->content) ?? [];
        } catch (\Exception $exception) {
            $responseContent = false;
        }

        $isOk = $response->isOk && (!$responseContent['Response']['Error'] || true);

        if ($isOk) {
            $cheque->success = true;
            $cheque->responseContent = Json::encode($responseContent);
            $cheque->save();
        }

        return $response;
    }

}