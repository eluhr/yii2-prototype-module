<?php

namespace dmstr\modules\prototype\controllers\api\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\rest\Action;

class UpdateAccessListAction extends Action
{
    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if (!Yii::$app->getUser()->getIsGuest()) {
            $model->addUserToAccessList(Yii::$app->getUser()->getId());
        } else {
            // Entry has not been modified
            $this->controller->response->setStatusCode(304);
        }

        return $model;
    }
}
