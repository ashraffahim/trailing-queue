<?php

/** @var yii\web\View $this */

$this->title = 'Register';
?>
<main class="flex min-h-screen flex-col items-center justify-between px-24" style="background-image: linear-gradient(black 40%, white 40%);">
    <div class="flex min-h-full flex-1 flex-col justify-center sm:mx-auto sm:w-full sm:max-w-md px-6 py-12 lg:px-8">
        <div class="flex flex-col sm:mx-auto sm:w-full">
            <div class="text-center text-4xl font-black text-white">
                TURF
            </div>
        </div>

        <?php if (count($errors) > 0): ?>
            <div class="p-3 text-xs bg-red-200">
                <b class="text-red-900"><?= array_values($errors)[0][0]; ?></b>
            </div>
        <?php endif; ?>

        <div class="mt-10 space-y-3 sm:mx-auto sm:w-full shadow-md p-5 rounded-md bg-white">
            <form method="POST" class="space-y-6">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />

                <div class="flex flex-row space-x-3">
                    <div>
                        <label for="first_name" class="input-label-classic">First name</label>
                        <div class="mt-2">
                            <input type="text" name="first_name" id="first_name" class="input-classic" autofocus required>
                        </div>
                    </div>
                    <div>
                        <label for="last_name" class="input-label-classic">Last name</label>
                        <div class="mt-2">
                            <input type="text" name="last_name" id="last_name" class="input-classic" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="email" class="input-label-classic">Email</label>
                    <div class="mt-2">
                        <input type="text" name="email" id="email" class="input-classic" required>
                    </div>
                </div>

                <div>
                    <label for="password" class="input-label-classic">Password</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" class="input-classic" required>
                    </div>
                </div>

                <div>
                    <label for="confirm_password" class="input-label-classic">Comfirm password</label>
                    <div class="mt-2">
                        <input type="password" name="confirm_password" id="confirm_password" class="input-classic" required>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="flex mt-6 w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 border-0">
                        Register
                    </button>
                </div>

                <div>
                    <p class="text-center text-sm text-gray-500">
                        Already a member?
                        <a href="/login" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</main>