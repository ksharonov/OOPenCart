<?php

namespace app\modules\profile\widgets\DocumentsWidget;

use app\helpers\ModelRelationHelper;
use app\models\session\ReconciliationSession;
use Yii;
use yii\base\Widget;
use app\models\db\User;
use app\models\search\DocumentSearchProfile;

/*
 * Виджет списка документов на странице пользователя
 */

class DocumentsWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $client = $user->client;

        // TODO потом возможно переделаем, чтобы триггерилось, когда все файлы просмотрены
        $acts = ReconciliationSession::get();
        $acts->deleteAwaitAll($client->id);
        $acts->save();

        $searchModel = new DocumentSearchProfile();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = $perPage ?? 5;

        $dataProvider->query->andWhere([
            'OR',
            [
                'relModel' => ModelRelationHelper::REL_MODEL_USER,
                'relModelId' => $user->id
            ],
            [
                'relModel' => ModelRelationHelper::REL_MODEL_CLIENT,
                'relModelId' => $client->id
            ]
        ]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'client' => $client
        ]);
    }
}