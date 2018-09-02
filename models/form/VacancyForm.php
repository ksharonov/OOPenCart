<?php

namespace app\models\form;


use app\models\base\Mailer;
use yii\base\Model;

/**
 *
 * Форма вакансий
 *
 * Class VacancyForm
 * @package app\models\form
 */
class VacancyForm extends Model
{
    public $id;
    public $name;
    public $phone;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['id', 'name', 'phone'], 'string']
        ];
    }

    public function send()
    {
        Mailer::sendToAdmin([
            'email' => 'no-reply@site.com',
            'name' => $this->name,
            'subject' => 'Резюме',
            'file' => $_FILES['file'],
            'body' => "Резюме от {$this->name}, {$this->phone}",
        ]);
    }
}