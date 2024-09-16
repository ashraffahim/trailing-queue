<?php

namespace app\controllers;

use app\components\PermissionManager;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class _MainController extends Controller {

    public $guestAuthorizedActions = [
        'site' => '*',
        'queues' => [
            'monitor',
            'monitor-socket',
        ]
    ];
    
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $this->handleGuestAccess();
        return parent::beforeAction($action);
    }

    public function handleGuestAccess() {
        $controllerName = $this->id;
        $actionName = $this->action->id;    
        
        if (Yii::$app->user->isGuest) {

            $redirect = true;
            
            if(array_key_exists($controllerName, $this->guestAuthorizedActions)) {
                if ($this->guestAuthorizedActions[$controllerName] === '*') {
                    $redirect = false;
                } elseif (array_key_exists($actionName, $this->guestAuthorizedActions[$controllerName])) {
                    $redirect = false;
                }
            }

            if ($redirect) {
                header('location: /login');
                die();
            }
        } else {
            $user = \Yii::$app->user->identity;
            $role = $user->role;

            $hasAccess = true;

            if (array_key_exists($controllerName, PermissionManager::TASK_PRIVILEGE)) {
                if (is_array(PermissionManager::TASK_PRIVILEGE[$controllerName])) {
                    if (array_key_exists($actionName, PermissionManager::TASK_PRIVILEGE[$controllerName])) {
                        if (!in_array(PermissionManager::TASK_PRIVILEGE[$controllerName][$actionName], PermissionManager::ROLE_PRIVILEGE[$role->task])) {
                            $hasAccess = false;
                        }
                    }
                } else if (!in_array(PermissionManager::TASK_PRIVILEGE[$controllerName], PermissionManager::ROLE_PRIVILEGE[$role->task])) {
                    $hasAccess = false;
                }
            }

            if (!$hasAccess) throw new ForbiddenHttpException();
        }
    }
}

?>