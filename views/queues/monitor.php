<?php

use app\assets\MonitorAsset;

/** @var array $roles */
/** @var array $ads */

MonitorAsset::register($this);
?>

<div id="queue-monitor" class="overflow-hidden h-screen">
    <div class="flex text-white" style="background-color: #069;">
        <div class="w-full h-[72px] flex items-center justify-center text-4xl uppercase font-bold p-3">
            <img src="/images/bangladesh-gov.png" class="h-[72px] mr-3">
            Consulate General of Bangladesh, Dubai
        </div>
    </div>
    <div class="flex flex-row w-full">
        <div id="queue-container" class="w-1/2 flex flex-col justify-between border border-emerald-100" style="height: CALC(100vh - 72px);">
            <div>
                <div class="flex items-center h-[72px] text-white" style="background-color: #069;">
                    <div class="text-5xl w-1/3">Token</div>
                    <div class="text-5xl w-1/3">Level</div>
                    <div class="text-5xl w-1/3">Count/Room</div>
                </div>
                <div id="queue" class="flex flex-col flex-auto"></div>
            </div>
            <div id="last-calls" class="flex flex-col text-9xl text-white" style="background-color: #069;">
                <div class="flex flex-row">
                    <div id="last-call-1-token" class="flex-auto border-r-2 border-emerald-100"></div>
                    <div id="last-call-1-room" class="flex-auto text-end"></div>
                </div>
                <div class="flex flex-row">
                    <div id="last-call-2-token" class="flex-auto border-r-2 border-emerald-100"></div>
                    <div id="last-call-2-room" class="flex-auto text-end"></div>
                </div>
            </div>
        </div>
        <div id="queue-extra-container" class="flex flex-col w-1/2">
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