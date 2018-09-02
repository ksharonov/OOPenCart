<?php

namespace app\system\base\widgets;


/**
 * Class ActiveField
 *
 * Переопределённый класс ActiveField
 *
 * @package app\system\base\widgets
 */
class ActiveField extends \yii\widgets\ActiveField
{
    public function widget($class, $config = [])
    {

        /* @var $class \yii\base\Widget | \app\system\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        if (is_subclass_of($class, 'app\system\base\widgets\InputWidget')) {
            $config['field'] = $this;
            if (isset($config['options'])) {
                $this->addAriaAttributes($config['options']);
                $this->adjustLabelFor($config['options']);
            }
        }

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }
}