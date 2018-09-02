<?php

namespace app\models\OneC;
use app\models\db\Client;
use app\models\db\ProductUnit;
use app\models\db\Unit;
use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */

class OrderClientOneC extends OneCLoader
{
    public $documentOneC = 'ЧекККМ';
    public $typeOneC = 'Document';

    public function sendOrderClientOneC (\app\models\db\Order &$order): bool
    {
        //echo $order->deliveryMethod . '<br>';
        //echo $order->id . '<br>';
        $items = $order->content;
        $sumBNDS = 0;
        $vat = 0;
        $sumNDS = 0;
        $goods = [];
        //$get_cashier_time = new CashierTimeOneC();
        $count = 0;
        $term = new TerminalOneC();
        $guid_terminal = $term->getGuidTerminalOneC();
        foreach ($items as $item) {
            /*echo 'ProductId - ', $item->product->guid . '<br>';
            echo 'EdizmId - ', $item->product->units[0]->guid . '<br>';
            echo 'Amount - ', $item->count . '<br>';
            echo 'Price - ', $item->priceValue . '<br>';
            echo 'SumBNds - ', $item->sumWVat . '<br>';
            echo 'NDS - ', $item->vat . '<br>';
            echo 'SumNDS - ', $item->sum . '<br>';
            echo 'DiscountSum - ', 0 . '<br>';
            echo 'DeliveryDate - ', date("Y-m-d H:i:s") . '<br>';*/
            //exit();
            $sumBNDS += $item->sumWVat;
            $vat += $item->vat;
            $sumNDS += $item->sum;
            $count++;
            $goods[] = [
                'LineNumber' => $count,
                //'Ref_Key' => 'a4fb5105-30d8-11e8-81c2-74d435d9f72e',
                //"ДатаОтгрузки" => "2018-03-26T00:00:00",
                //"Номенклатура_Key" =>  "1f035ef0-26ad-11e8-a4b2-50e549e85972",
                "Номенклатура_Key" => $item->product->guid,
                //"Характеристика_Key" => "00000000-0000-0000-0000-000000000000",
                //"Упаковка_Key" => "00000000-0000-0000-0000-000000000000",
                //"КоличествоУпаковок" =>  5,
                //"Количество" =>  5,
                "КоличествоУпаковок" => $item->count,
                "Количество" => $item->count,
                //"ВидЦены_Key" =>  "00000000-0000-0000-0000-000000000000",
                "ВидЦены_Key" => "0c4ae499-ef50-11e7-b73e-50e549e85972",
                //"Цена" =>  200,
                //"Сумма" =>  1000,
                "Цена" => $item->priceValue,
                "Сумма" => $item->sum,
                "СтавкаНДС" => "БезНДС",
                "СуммаНДС" => 0,
                "СуммаСНДС" => $item->sum,
                //"ПроцентРучнойСкидки" =>  0,
                //"СуммаРучнойСкидки" =>  0,
                //"ПроцентАвтоматическойСкидки" =>  0,
                //"СуммаАвтоматическойСкидки" =>  0,
                //"ПричинаОтмены_Key" =>  "00000000-0000-0000-0000-000000000000",
                "КодСтроки" => $count,
                "Отменено" => false,
                "КлючСвязи" => $count,
                "Склад_Key" => "2edeff06-1cd1-11e8-ae20-50e549e85972",
                "Продавец_Key" => "cb30a85e-ef6b-11e7-b73e-50e549e85972",
                "СрокПоставки" => "0",
                "Содержание" => "",
                //"СтатусУказанияСерий" => 0,
                "ВариантОбеспечения" => "СоСклада",
                //"Серия_Key" => "00000000-0000-0000-0000-000000000000",
                //"НоменклатураНабора_Key" => "00000000-0000-0000-0000-000000000000",
                //"ХарактеристикаНабора_Key" => "00000000-0000-0000-0000-000000000000"
            ];
        }
        $this->data = [
            "Ref_Key"=> "00000000-0000-0000-0000-000000000000",
            //"Number" => "Т-000003",
            "DataVersion"=> "AAAABgAAAAA=",
            "DeletionMark"=> false,
            //"Date"=> "2018-03-28T15:00:00",
            //"Date"=> strval(date("Y-m-d\TH:i:s")),
            "Date"=> strval(date("Y-m-d\TH:i:s", time() + 32400)),
            //"Posted"=> true,
            "Архивный"=> false,
            "Валюта_Key"=> "4a8f4a42-ef50-11e7-b73e-50e549e85972",
            "ВидЦены_Key"=> "0c4ae498-ef50-11e7-b73e-50e549e85972",
            //"КассаККМ_Key"=> "2122b2ad-26ed-11e8-a4b2-50e549e85972",
            //"КассаККМ_Key"=> "c2fe7d98-3255-11e8-8e96-0025908038af",
            "КассаККМ_Key"=> $guid_terminal,
            "Кассир_Key"=> "cb30a85e-ef6b-11e7-b73e-50e549e85972",
            "Комментарий"=> "Документ сформирован через терминал",
            "НалогообложениеНДС"=> "ПродажаНеОблагаетсяНДС",
            "Организация_Key"=> "c294f740-0199-11e8-bec4-9cad97235578",
            "ПолученоНаличными"=> 0,
            "СкидкиРассчитаны"=> true,
            "Склад_Key"=> "2edeff06-1cd1-11e8-ae20-50e549e85972",
            //"Статус"=> "Пробит",
            "Статус"=> "Отложен",
            "СуммаДокумента"=> $sumNDS,
            "ФормаОплаты"=> "Наличная",
            "ЦенаВключаетНДС"=> true,
            //"ОтложенДо"=> "0001-01-01T00:00:00",
            "Партнер_Key"=> "32914b1b-ef50-11e7-b73e-50e549e85972",
            "ИспользоватьОплатуБонуснымиБаллами"=> false,
            //"КассоваяСмена_Key"=> "0df1e8ff-3704-11e8-81c2-74d435d9f72e",
            //"КассоваяСмена_Key"=> '"'.$get_cashier_time->getGuidCashierTimeOneC().'"',
            //"Товары"=> [json_encode($goods)],
            //"ЭтапыГрафикаОплаты"=> [json_encode($shedule_payment)],
            "Товары"=> [$goods],
            "ОплатаПлатежнымиКартами"=> [],
            "СкидкиНаценки"=> [],
            "Серии"=> [],
            "ПодарочныеСертификаты"=> [],
            "БонусныеБаллы"=> [],
            "АкцизныеМарки"=> []
        ];

        $test = new TestConnectOneC();
        $result = $test->addTestConnectOneC();
//        dump($result);
        if ($result){
            //echo 'good connection';
            $d = $this->createObjOneC();

            $new_outer_rel = new OuterRel();
            $new_outer_rel->guid = json_decode($d->content)->Ref_Key;
            $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_ORDER;
            $new_outer_rel->relModelId = $order->id;
            $new_outer_rel->save(false);

            return true;
        } else{
            //echo 'bad connection';
            return false;
        }
    }
}