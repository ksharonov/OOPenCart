<?php

namespace app\widgets\SmsPopupWidget;

use yii\base\Widget;

/**
 * Class SmsPopupWidget
 * @package app\widgets\SmsPopupWidget
 * Пока просто модалка без всяких обработчиков
 */
class SmsPopupWidget extends Widget
{
    public function run()
    {
        return $this->render('_popup_sms');
    }
}