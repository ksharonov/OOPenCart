<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Для тестов. Сейчас здесь пример по СБЕРУ. юзернеймы и другие урлы отправляем в БД
 * Класс SberbankPay кидай куда нужно отдельным файлом
 */
class PavelControllerDel extends Controller {

    /**
     * Пример получения инфы о заказе. Сюда же возвращает СБ
     * @param string $orderId GET параметр
     */
    public function actionOrnotok($orderId = null) {
        $sb = (new SberbankPay())
                ->setUrnBase("https://3dsec.sberbank.ru/payment/rest")
                ->setApiUsername("220v2_energosi-api")
                ->setApiPassword("220v2_energosi");

        $result = $sb->getOrderStatus($orderId);

        print_r($result);
    }

    public function actionRegisterpay() {

        $sb = (new SberbankPay())
                ->setUrnBase("https://3dsec.sberbank.ru/payment/rest")
                ->setApiUsername("220v2_energosi-api")
                ->setApiPassword("220v2_energosi");

        $result = $sb->register(10, 5, "http://220v.local/pavel/ornotok");

        print_r($result);
    }

}

class SberbankPay {

    const URN_CMD_REGISTER = "/register.do";
    const URN_CMD_GET_ORDER_STATUS = "/getOrderStatus.do";

    private $urnBase = null;
    private $apiUsername = null;
    private $apiPassword = null;

    /**
     * Получение статуса зказа по его ID
     * @param string $orderId ИД заказа (то что вернул СБ при регистрации)
     * @return JSON Информацию о заказа. Описание смотри в этом классе внизу
     */
    public function getOrderStatus(string $orderId) {
        $result = $this->request(self::URN_CMD_GET_ORDER_STATUS, [
            "orderId" => $orderId,
        ]);

        return $result;
    }

    /**
     * Регистрирует платеж в сбербанке.
     * @param float $amount Сумма платежа
     * @param int $orderNumber Номер заказа в нашей системе
     * @param string $returnUrl Адрес формы возврата пользователя     
     * @param string $description Описание заказа
     * @param string $clientId ИД Клиента
     * @return JSON Объект с orderId (номер заказа в СБ) и formUrl (куда отправлять юзера на оплату)
     */
    public function register(float $amount, int $orderNumber, string $returnUrl, string $description = null, string $clientId = null) {

        $result = $this->request(self::URN_CMD_REGISTER, [
            "orderNumber" => $orderNumber,
            "amount" => $amount,
            "returnUrl" => $returnUrl,
            "description" => $description,
            "clientId" => $clientId,
        ]);

        return $result;
    }

    /**
     * Запрос в API СБ мерчант
     * @param string $urnCmd Команда. Должна начинаться со слеша
     * @param array $params Список параметров=>значение
     * @return JSON Объект ответа СБ API
     */
    private function request(string $urnCmd, array $params = []) {
        $authParams = [
            "userName" => $this->apiUsername,
            "password" => $this->apiPassword,
        ];

        $httpQuery = http_build_query(array_merge($authParams, $params));
        $result = file_get_contents($this->urnBase . $urnCmd . "?" . $httpQuery);
        $resultJson = json_decode($result);
        return $resultJson;
    }

    /**
     * WEB Путь к REST-API сервису Сбербанк
     * @param string $urnBase
     * @return $this
     */
    public function setUrnBase($urnBase) {
        $this->urnBase = $urnBase;
        return $this;
    }

    /**
     * Установить имя пользователя API мерчанта
     * @param string $apiUsername
     * @return $this
     */
    public function setApiUsername($apiUsername) {
        $this->apiUsername = $apiUsername;
        return $this;
    }

    /**
     * Установить пароль API мерчанта
     * @param string $apiPassword
     * @return $this
     */
    public function setApiPassword($apiPassword) {
        $this->apiPassword = $apiPassword;
        return $this;
    }

    /*
     * Поля в статусе заказа:
      (
      [expiration] => 201912
      [cardholderName] => CARDHOLDER NAME
      [depositAmount] => 10
      [currency] => 643
      [approvalCode] => 123456
      [authCode] => 2
      [ErrorCode] => 0
      [ErrorMessage] => Успешно
      [OrderStatus] => 2
      [OrderNumber] => 2
      [Pan] => 411111XXXXXX1111
      [Amount] => 10
      [Ip] => 109.195.149.78
      )
     */
}
