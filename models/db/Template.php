<?php

namespace app\models\db;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property string $name
 * @property string $params
 * @property string class
 */
class Template extends \app\system\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['params'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['class', 'title'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя шаблона',
            'title' => 'Название шаблона',
            'params' => 'Params',
            'class' => 'Class'
        ];
    }

    public function beforeSave($insert)
    {
        $this->params = Json::encode($this->params);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->params = Json::decode($this->params);
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $this->params = Json::decode($this->params);
        parent::afterFind();
    }

}
