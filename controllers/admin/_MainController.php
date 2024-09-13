<?php

namespace app\controllers\admin;

use Yii;
use yii\web\Controller;

class _MainController extends Controller {

    public $guestAuthorizedActions = [
        'site' => '*',
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
        if (Yii::$app->user->isGuest) {
            $controllerName = $this->id;
            $actionName = $this->action->id;    

            $redirect = true;
            
            if(array_key_exists($controllerName, $this->guestAuthorizedActions)) {
                if ($this->guestAuthorizedActions[$controllerName] === '*') {
                    $redirect = false;
                } elseif (array_key_exists($actionName, $this->guestAuthorizedActions)) {
                    $redirect = false;
                }
            }

            if ($redirect) {
                header('location: /login');
                die();
            }
        }
    }
}

?>