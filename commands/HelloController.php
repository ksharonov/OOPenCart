<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\MailHelper;
use app\models\db\Order;
use app\models\db\Product;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    /**
     * Тест динамических методов (зашкварно, окей)
     */
    public function actionTest()
    {
//        MailHelper::newOrder(Order::findOne(1));
        $order = Order::findOne(11111118);
        $cancel = $order->action->cancel();
        dump($cancel);

//        $ok = Product::find()->all();
//        dump($ok);
        //        $test = new class
//        {
//            function __construct()
//            {
//                $this->param = function () {
//                    var_dump(2);
//                };
//            }
//
//            function test()
//            {
//                var_dump(1);
//            }
//        };
//
//        $test->test();
//        ($test->param)();

    }
}
