<?php

use app\assets\MonitorAsset;

/** @var array $roles */

MonitorAsset::register($this);
?>

<div class="queues-monitor">
    <div id="role-columns" class="flex w-full">
    </div>
    <div id="role-modal" class="fixed inset-0 flex flex-col justify-center items-center">
        <div class="fixed inset-0 bg-black opacity-30"></div>
        <div id="role-modal-backdrop" class="fixed inset-0 backdrop-blur-md"></div>
        <div id="role-select" class="relative flex flex-col justify-center items-center gap-y-3 p-6 rounded-md bg-slate-100 shadow-lg max-w-lg w-full overflow-x-auto">
        </div>
    </div>
</div>
<script type="text/javascript">
window.queueRoles = <?= json_encode($roles) ?>;
</script>