<?php

namespace app\controllers;

use app\components\Util;
use app\models\databaseObjects\Account;
use app\models\databaseObjects\User;
use app\models\exceptions\common\CannotSaveException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;

use function PHPUnit\Framework\throwException;

class SiteController extends _MainController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) return $this->redirect('/login');

        return $this->render('index');
    }

    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome('/admin');
        }

        $errors = [];

        if (Yii::$app->request->isPost) {

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $user = new User();
    
                $password = Yii::$app->request->post('User[password_hash]');

                $user->first_name = Yii::$app->request->post('first_name');
                $user->last_name = Yii::$app->request->post('last_name');
                $user->username = Yii::$app->request->post('username');
                $user->email = Yii::$app->request->post('email');
                $user->password_hash = Yii::$app->security->generatePasswordHash($password);
    
                if (!$user->save()) {
                    if ($user->hasErrors()) {
                        $errors = $user->getErrors();
                    }
                    
                    throw new CannotSaveException($user);
                }

                $transaction->commit();

                return $this->redirect('/login');

            } catch (\Exception $e) {
                $transaction->rollBack();

                // throw $e;
            }
        }

        $this->layout = 'blank';
        return $this->render('register', ['errors' => $errors]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome('/admin');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack('/');
        }

        $model->password = '';

        $this->layout = 'blank';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
