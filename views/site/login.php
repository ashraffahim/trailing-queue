<?php

/** @var yii\web\View $this */

use yii\widgets\ActiveForm;

/** @var app\models\forms\LoginForm $model */

$this->title = 'Login';
?>
<main class="flex min-h-screen flex-col items-center justify-between px-24" style="background-image: linear-gradient(black 40%, white 40%);">
    <div class="flex min-h-full flex-1 flex-col justify-center sm:mx-auto sm:w-full sm:max-w-md px-6 py-12 lg:px-8">

        <div class="flex flex-col sm:mx-auto sm:w-full">
            <div class="text-center text-4xl font-black text-white">
                QUEUE
            </div>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full shadow-md p-5 rounded-md bg-white">
            <?php
            $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{input}\n{error}",
                    'errorOptions' => ['class' => 'input-error-text-classic'],
                ],
                'options' => [
                    'class' => 'space-y-6'
                ]
            ]);
            ?>
            <form class="space-y-6" method="POST">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
                <div>
                    <label for="email" class="input-label-classic">
                        Email address
                    </label>
                    <div class="mt-2">
                        <?= $form->field($model, 'username')->textInput([
                            'class' => 'input-classic',
                            'autoComplete' => 'email',
                            'autofocus' => true,
                            'required' => true
                        ]); ?>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="input-label-classic">
                            Password
                        </label>
                        <div class="text-sm">
                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500" tabindex="-1">
                                Forgot password?
                            </a>
                        </div>
                    </div>
                    <div class="mt-2">
                        <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'input-classic',
                            'autoComplete' => 'current-password',
                            'required' => true
                        ]); ?>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 border-0">
                        Sign in
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500">
                    Not a member?
                    <a href="/register" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Start a 14 day free trial</a>
                </p>

                <?php ActiveForm::end(); ?>
                </p>
        </div>
    </div>
</main>