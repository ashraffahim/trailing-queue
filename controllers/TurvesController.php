<?php

namespace app\controllers;

use app\components\Util;
use app\models\databaseObjects\Turf;
use app\controllers\_MainController;
use app\models\exceptions\common\CannotSaveException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TurvesController implements the CRUD actions for Turf model.
 */
class TurvesController extends _MainController
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
     * Lists all Turf models.
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
     * Displays a single Turf model.
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
     * Creates a new Turf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Turf();

        if ($this->request->isPost) {

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->load($this->request->post())) {

                    $model->nid = Util::nanoid(Turf::class);
                    $model->account_id = Yii::$app->user->identity->account->id;

                    if (!$model->validate()) {
                        throw new \InvalidArgumentException();
                    }

                    if (!$model->save()) {
                        throw new CannotSaveException($model);
                    }
                } else {
                    throw new \InvalidArgumentException();
                }
                
                $transaction->commit();

                return $this->redirect(['view', 'nid' => $model->nid]);

            } catch (\InvalidArgumentException $e) {
                
                $transaction->rollBack();

            } catch (\Exception $e) {
                $transaction->rollBack();

                throw $e;
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Turf model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $nid NID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($nid)
    {
        $model = $this->findModel($nid);

        if ($this->request->isPost) {

            $transaction = Yii::$app->db->beginTransaction();
            
            try {

                if ($model->load($this->request->post())) {

                    if (!$model->validate()) {
                        throw new \InvalidArgumentException();
                    }

                    if (!$model->save()) {
                        throw new CannotSaveException($model);
                    }

                } else {
                    throw new \InvalidArgumentException();
                }
                
                $transaction->commit();

                return $this->redirect(['view', 'nid' => $model->nid]);
            
            } catch (\InvalidArgumentException $e) {
                        
                $transaction->rollBack();

            } catch (\Exception $e) {
                $transaction->rollBack();

                throw $e;
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Turf model.
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
     * Finds the Turf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $nid NID
     * @return Turf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($nid)
    {
        if (($model = Turf::findOne(['nid' => $nid, 'account_id' => Yii::$app->user->identity->account->id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
