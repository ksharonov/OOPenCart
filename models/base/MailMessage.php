<?php

namespace app\models\base;

use yii\base\BaseObject;

/**
 * Class MailMessage
 *
 * Класс для инициализации сообщения
 *
 * @package app\models\base
 */
class MailMessage extends BaseObject
{
    /**
     * @var string
     */
    public $toEmail;

    /**
     * @var string
     */
    public $toName;

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $fromName;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $message;

    /**
     * @var
     */
    public $file;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['toEmail', 'toName', 'fromEmail', 'fromName', 'subject', 'message'], 'string'],
            [['toEmail', 'toName', 'fromEmail', 'fromName', 'subject', 'message'], 'required'],
            [['file'], 'safe']
        ];
    }
}