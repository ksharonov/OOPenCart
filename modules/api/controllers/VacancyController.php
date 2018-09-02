<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15-05-2018
 * Time: 11:08 AM
 */

namespace app\modules\api\controllers;


use app\system\base\ApiController;
use app\models\search\PostSearch;
use app\models\db\Post;
use app\models\base\post\Vacancy;
use yii\helpers\Json;

class VacancyController extends ApiController
{
    public function actionIndex($id = null)
    {
        $model = Post::find()->where(['id' => $id, 'type' => Post::TYPE_VACANCY])->one();
        if ($id && $model) {
            return [
                "title" => $model->title,
                "text" => $model->content,
                "experience" => $model->param->experience,
                "city" => $model->param->city
            ];
        }
    }
}