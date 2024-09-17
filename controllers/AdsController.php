<?php

namespace app\controllers;

use app\models\forms\AdsUploadForm;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class AdsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $filesArray = glob(\Yii::getAlias('@app/web/data/ads/*'));
        return $this->render('index', [
            'files' => $filesArray
        ]);
    }

    public function actionUpload() {
        $model = new AdsUploadForm();

        if (!$this->request->isPost) throw new NotFoundHttpException();
        
        $model->files = UploadedFile::getInstances($model, 'files');
        if ($model->upload()) {
            return $this->asJson($model);
        } else {
            return $this->asJson($model->errors);
        }

    }

    public function actionDelete($name) {
        unlink(\Yii::getAlias('@app/web/data/ads/') . $name);
        return $this->redirect(['index']);
    }

}
