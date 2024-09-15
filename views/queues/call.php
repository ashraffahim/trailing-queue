<?php

/** @var array $forwardRoles */
/** @var string $openCloseText */

use app\assets\CallAsset;

CallAsset::register($this);
?>
<div class="queue-call">
    <div class="flex justify-center items-center w-full min-h-screen sm:px-10 px-1">
        <div class="flex flex-col w-full max-w-6xl h-full">
            <div class="flex w-full">
                <div id="current-token" class="flex justify-center items-center h-28 text-5xl font-black w-1/2 bg-slate-200"></div>
                <button id="next-token" class="flex justify-center items-center h-28 text-5xl w-1/2 bg-emerald-400">NEXT</button>
            </div>
            <div class="flex w-full">
                <button id="start-stop-service" class="flex justify-center items-center h-16 flex-auto bg-lime-100"><?= $openCloseText ?></button>
                <button id="recall" class="flex justify-center items-center h-16 flex-auto bg-amber-100">Recall</button>
                <button id="forward-token" class="flex justify-center items-center h-16 flex-auto bg-cyan-100">Forward</button>
            </div>
    
            <div class="flex w-full mt-6">
                <div id="time-ellapsed" class="flex justify-center items-center h-16 flex-auto bg-slate-100">00:00</div>
                <div id="token-count" class="flex justify-center items-center h-16 flex-auto bg-slate-100"></div>
                <div id="token-served" class="flex justify-center items-center h-16 flex-auto bg-slate-100"></div>
                <div id="token-left" class="flex justify-center items-center h-16 flex-auto bg-slate-100"></div>
            </div>
        </div>
    </div>
    <div id="forward-modal" class="fixed inset-0 flex justify-center items-center hidden">
        <div class="fixed inset-0 bg-black opacity-30"></div>
        <div id="forward-modal-backdrop" class="fixed inset-0 backdrop-blur-md"></div>
        <div id="role-list" class="relative flex flex-col gap-y-3 p-6 rounded-md bg-slate-100 shadow-lg max-w-lg w-full overflow-x-auto">
            <button id="hide-forward-modal" class="flex justify-center items-center w-full h-16 text-red-600 rounded-md">Close</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.forwardRoles = <?= json_encode($forwardRoles) ?>;
</script>