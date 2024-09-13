<?php

namespace app\controllers;

use app\models\databaseObjects\Slot;
use app\controllers\_MainController;
use app\models\databaseObjects\Turf;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SlotsController implements the CRUD actions for Slot model.
 */
class SlotsController extends _MainController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Slot models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = Turf::findAll(['account_id' => Yii::$app->user->identity->account_id]);

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Slot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($nid)
    {
        $turf = Turf::findOne([
            'account_id' => Yii::$app->user->identity->account_id,
            'nid' => $nid
        ]);

        if (is_null($turf)) throw new NotFoundHttpException('The requested page does not exist.');

        $model = Slot::findAll(['turf_id' => $turf->id]);

        if ($this->request->isPost) {
            $this->response->format = Response::FORMAT_JSON;

            return $this->request->post();
        }

        return $this->render('update', [
            'turf' => $turf,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Slot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($nid)
    {
        $this->findModel($nid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Slot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Slot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($nid)
    {
        $model = Slot::findOne(['nid' => $nid]);

        if (!is_null($model) && $model->turf->account_id === Yii::$app->user->identity->account_id) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
