<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15-12-2017
 * Time: 15:32 PM
 */

namespace app\modules\admin\widgets\client\ClientUsersWidget;

use Yii;
use app\models\search\UserSearch;
use yii\base\Widget;
use app\models\db\Client;
use app\models\db\User;
use yii\data\ActiveDataProvider;

/**
 * Виджет для добавления пользователей клиенту
 *
 * @property Client $model
 */
class ClientUsersWidget extends Widget
{
    public $model = null;

    public function run()
    {
        $view = $this->getView();
        ClientUsersAsset::register($view);

        $userSearchModel = new UserSearch();
        $userProvider = $userSearchModel->search(Yii::$app->request->queryParams);
        $userProvider->pagination->pageSize = 10;

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()
                ->joinWith('clients')
                ->where(['clientId' => $this->model->id]),
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);

        return $this->render('index', [
            'clientModel' => $this->model,
            'dataProvider' => $dataProvider,
            'userSearchModel' => $userSearchModel,
            'userProvider' => $userProvider
        ]);
    }
}