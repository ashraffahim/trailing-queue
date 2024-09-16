<?php

use app\assets\KioskAsset;

/** @var yii\web\View $this */
/** @var array $roles */


$this->title = 'Queues';
$this->params['breadcrumbs'][] = $this->title;

KioskAsset::register($this);
?>
<div class="queues-kiosk">
    <div class="flex flex-col justify-center items-center gap-y-3 py-3" id="role-list">
        <div class="flex flex-col justify-center items-center mb-6">
            <img src="/images/bangladesh-embassy.jpg" alt="Bangladesh Embassy" class="h-36">
            <div class="my-3 text-xs uppercase text-slate-400 ">Sponsored by</div>
            <div class="flex gap-x-3">
                <div class="w-1/2">
                    <img src="/images/amaar-clinic-logo.png" alt="Amaar Clinic" class="h-10 grayscale">
                </div>
                <div class="w-1/2">
                    <img src="/images/amc-logo.png" alt="Aser Al Madina" class="h-10 grayscale">
                </div>
            </div>
        </div>
    </div>
    <div id="token-modal" class="fixed inset-0 flex flex-col justify-center items-center hidden">
        <div class="fixed inset-0 bg-black opacity-30"></div>
        <div id="token-modal-backdrop" class="fixed inset-0 backdrop-blur-md"></div>
        <div id="token-print" class="relative flex flex-col justify-center items-center gap-y-3 p-6 rounded-md bg-slate-100 shadow-lg max-w-lg w-full overflow-x-auto">
        </div>
    </div>
</div>
<script type="text/javascript">
    window.printRequestURL = '<?= \Yii::$app->params['printRequestURL'] ?>';
    window.tokenRoles = <?= json_encode($roles) ?>;
</script>