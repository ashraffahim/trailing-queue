<?php

use app\assets\MonitorAsset;

/** @var array $roles */
/** @var array $ads */

MonitorAsset::register($this);
?>

<div class="queues-monitor overflow-hidden h-screen">
    <div class="flex flex-row w-full">
        <div id="queue-container" class="w-1/2">
            <div id="queue" class="flex flex-col flex-auto min-h-screen border border-emerald-100"></div>
        </div>
        <div class="flex flex-col w-1/2">
            <div id="ads" class="flex justify-center items-center w-full h-[60vh]"></div>
            <div id="role-columns" class="flex flex-row w-full"></div>
        </div>
    </div>
    <div id="role-modal" class="fixed inset-0 flex flex-col justify-center items-center">
        <div class="fixed inset-0 bg-black opacity-30"></div>
        <div id="role-modal-backdrop" class="fixed inset-0 backdrop-blur-md"></div>
        <div id="role-select" class="relative flex flex-col justify-center items-center gap-y-3 p-6 rounded-md bg-slate-100 shadow-lg max-w-lg w-full overflow-x-auto">
        </div>
    </div>
</div>
<script type="text/javascript">
window.ads = <?= json_encode($ads) ?>;
window.queueRoles = <?= json_encode($roles) ?>;
</script>