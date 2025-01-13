<?php

namespace dmstr\modules\prototype\controllers\api;

/**
 * This is the class for REST controller "LessController".
 */

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;

class LessController extends ActiveController
{
    public $modelClass = 'dmstr\modules\prototype\models\Less';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['update-access-list'] = [
            'class' => 'dmstr\modules\prototype\controllers\api\actions\UpdateAccessListAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
        ];
        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->can(
                            $this->module->id . '_' . $this->id . '_' . $action->id,
                            ['route' => true]
                        );
                    }
                ]
            ]
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'update-access-list' => ['PATCH'],
            ]
        ];
        return $behaviors;
    }
}
