<?php

namespace app\system\base\widgets;

use Yii;
use yii\base\InvalidCallException;
use app\system\base\widgets\ActiveField;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveForm
 *
 * Переопределённый ActiveForm
 *
 * @package app\system\base\widgets
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    public $fieldClass = 'app\system\base\widgets\ActiveField';

    /**
     * Ends a form field.
     * This method will return the closing tag of an active form field started by [[beginField()]].
     * @return string the closing tag of the form field.
     * @throws InvalidCallException if this method is called without a prior [[beginField()]] call.
     */
    public function endField()
    {
        $field = array_pop($this->_fields);
        if ($field instanceof ActiveField) {
            return $field->end();
        }

        throw new InvalidCallException('Mismatching endField() call.');
    }

    public function field($model, $attribute, $options = [])
    {

        $config = $this->fieldConfig;
        if ($config instanceof \Closure) {
            $config = call_user_func($config, $model, $attribute);
        }

        if (!isset($config['class'])) {
            $config['class'] = $this->fieldClass;
        }

        return Yii::createObject(ArrayHelper::merge($config, $options, [
            'model' => $model,
            'attribute' => $attribute,
            'form' => $this,
        ]));
    }
}