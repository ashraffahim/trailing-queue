<?php

namespace app\controllers;

use app\models\databaseObjects\Booking;
use yii\data\ActiveDataProvider;
use app\controllers\_MainController;
use app\models\databaseObjects\Turf;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookingsController implements the CRUD actions for Booking model.
 */
class BookingsController extends _MainController
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
     * Lists all Booking models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Booking::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Booking model.
     * @param int $nid NID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($nid)
    {
        return $this->render('view', [
            'model' => $this->findModel($nid),
        ]);
    }

    /**
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Booking();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect('/bookings/view/' . $model->nid);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'turves' => Turf::find()->select(['name', 'nid'])->all()
        ]);
    }

    /**
     * Updates an existing Booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $nid NID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($nid)
    {
        $model = $this->findModel($nid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect('/bookings/view/' . $model->nid);
        }

        return $this->render('update', [
            'model' => $model,
            'turves' => Turf::find()->select(['name', 'nid'])->all()
        ]);
    }

    /**
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $nid NID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($nid)
    {
        $this->findModel($nid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $nid NID
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($nid)
    {
        if (($model = Booking::findOne(['nid' => $nid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
