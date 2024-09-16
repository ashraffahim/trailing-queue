<?php

namespace app\controllers;

use app\controllers\_MainController;

/**
 * Reports Controller.
 */
class ReportsController extends _MainController
{
    public function actionIndex() {
        return $this->render('index');
    }
}
