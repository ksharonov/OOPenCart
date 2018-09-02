<?php

namespace app\models\OneC;
use app\models\db\Client;
use app\models\db\OrderContent;
use app\models\db\ProductUnit;
use app\models\db\Setting;
use app\models\db\Unit;
use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;
use app\models\db\OrderStatusHistory;
use yii\helpers\Json;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */

class CheckStatusOrdersOneC extends OneCLoader
{

    public function CheckStatusOrdersOneC ()
    {

        /*$spis = OrderStatusHistory::find()
            ->select('orderId')
            ->where(['orderStatusId' => 5])
            ->orderBy('dtCreate', 'desc')
            ->asArray()
            ->all();
        dump($spis);*/

        //TODo поместить в сеттинг статус orderStatusId где статус заверншен
        $vendorCodesArray = OrderStatusHistory::find()
            //->select('product.id, title, vendorCode')
            //->where(['not in', 'orderId', OrderStatusHistory::find()->select('orderId')->where(['orderStatusId' => 5])->orWhere(['orderStatusId' => 3])->orderBy('dtCreate', 'desc')->asArray()->all()])
            ->where(['not in', 'orderId', OrderStatusHistory::find()->select('orderId')->where(['orderStatusId' => Setting::get('ORDER.STATUS.FINISH')])->orderBy('dtCreate', 'desc')->asArray()->all()])
            ->groupBy('orderId')
            ->asArray()
            ->all();
        dump($vendorCodesArray);

        foreach ($vendorCodesArray as $key) {
            //dump ($key['orderId']);
            $guid_order = OuterRel::findOne([
                'relModelId' => $key['orderId'],
                'relModel' => ModelRelationHelper::REL_MODEL_ORDER,
            ]);
            //dump($guid_order['guid']);

            // находит текущий чек в 1С и проверяет его статус
            // если статус "пробит", то меняет статус в базе
            $this->typeOneC = 'Document';
            $this->documentOneC = "ЧекККМ(guid'".$guid_order['guid']."')";
            $get_product = $this->getObjOneC();
            if ($get_product){
                $array_attr_product = json_decode($get_product->content);
                dump($array_attr_product);
                //dump (json_decode($get_product->content)->Статус);
                if (json_decode($get_product->content)->Статус == 'Пробит'){
                    //echo 'пробит, заебись <br>' ;
                    $new_status_order = New OrderStatusHistory();
                    $new_status_order->orderId = $key['orderId'];
                    $new_status_order->orderStatusId = Setting::get('ORDER.STATUS.FINISH');
                    //$new_status_order->save(false);
                } else {
                    //echo 'отложен, хуева<br>' ;
                }

                $id_product_in_onec = [];
                $array_products_in_onec = [];
                $array_products_in_base = [];

                // получаем список товаров по текущему чеку в 1с
                // выносим ID товаров в отдельный массив
                $products = json_decode($get_product->content)->Товары;
                foreach ($products as $product){
                    //dump($item->Номенклатура_Key);
                    $find_product = Product::findByGuid($product->Номенклатура_Key);
                    $id_product_in_onec[] = $find_product->id;
                    $q = [];
                    $q['id'] = $find_product->id;
                    $q['guid'] = $product->Номенклатура_Key;
                    $q['count'] = $product->Количество;
                    $q['price'] = $product->Цена;
                    $array_products_in_onec[] = $q;
                }
                //dump($array_products_in_onec);


                // получает список товаров по текущему чеку в базе
                // также выносим ID товаров в отдельный массив
                $products_in_order_base = OrderContent::find()
                    ->select('productId')
                    ->where(['orderId' => $key['orderId']])
                    ->asArray()
                    ->all();
                foreach ($products_in_order_base as $q){
                    $array_products_in_base[] = $q['productId'];
                }
                //dump($array_products_in_base);



                // сравнивает товары чека из 1С с товарами чека в базе
                //в случае если товар не найден, то добавляет запись в OrderContent
                foreach ($array_products_in_onec as $product_in_onec) {
                    $k = array_search($product_in_onec['id'], $array_products_in_base);
                    if ($k === false){
                        echo 'alarm pizdec <br>';
                        $new_order_content = new OrderContent();
                        $new_order_content->orderId = $key['orderId'];
                        $new_order_content->productId = $product_in_onec['id'];
                        $new_order_content->priceValue = $product_in_onec['price'];
                        $new_order_content->count = $product_in_onec['count'];
                        $new_order_content->productData = Json::encode($find_product->data);
                        $new_order_content->save(false);
                    }else {
                        echo '$k = '. $k . ' = '. $product_in_onec['id'].'<br>';
                        $order_content = OrderContent::find()
                            ->where(['orderId' => $key['orderId']])
                            ->andWhere(['productId' => $product_in_onec['id']])
                            ->one();
                        //dump($order_content);
                        //dump($product_in_onec);
                        $order_content->priceValue = $product_in_onec['price'];
                        $order_content->count = $product_in_onec['count'];
                        $order_content->save(false);
                        //dump($find_product->data);
                    }
                }

                // сравнивет товары чека в базе с товарами чека в 1С
                // в случае расхождения с чеком из 1с, удаляет запись в OrderContent по товару которого нет в 1С
                foreach ($array_products_in_base as $product_in_base){
                    dump($product_in_base);
                    $k = array_search($product_in_base, $id_product_in_onec);
                    if ($k === false){
                        echo 'alarm pizdec и здесь тоже <br>';
                        $order_content = OrderContent::find()
                            ->where(['orderId' => $key['orderId']])
                            ->andWhere(['productId' => $product_in_base])
                            ->one();
                        $order_content->delete();
                    }else {
                        echo '2$k = '. $k . ' = '. $product_in_base .'<br>';
                        //dump($find_product->data);
                    }
                }
            }
        }

    }
}