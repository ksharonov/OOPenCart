<?php

namespace app\models\cookie;

use app\system\db\ActiveRecordCookie;

/**
 * Class UserCookie
 * @package app\models\cookie
 *
 * @property boolean $isEntity
 */
class UserCookie extends ActiveRecordCookie
{
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['isEntity', 'boolean']
        ];
    }
}