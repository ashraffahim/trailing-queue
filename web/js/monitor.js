let queues = [null, null, null];
let lastLoadedId = 0;
let firstLoadedId = 0;

const fetchUrl = {
    monitorSocket: '/queues/monitor-socket'
};

const roleSelectElement = $('#role-select');
const roleColumnsElement = $('#role-columns');

const roleModalElement = $('#role-modal');

queues.forEach((num, index) => {
    const select = $('<select class="text-lg"></select>');

    window.queueRoles.forEach(role => {
        select.append('<option value="' + role.id + '">' + role.name + '</option>');
    });

    queues[index] = window.queueRoles[0].id;

    select.change('click', () => {
        queues[index] = select.val();
    });

    roleSelectElement.append(select);
});

const startButtonElement = $('<button class="text-lg">START</button>');

startButtonElement.on('click', () => {
    roleModalElement.addClass('hidden');

    queues.forEach(role => {
        roleColumnsElement.append(`
            <div id="role-column-${role}" class="flex flex-col flex-auto min-h-screen border border-slate-400">
                <div class="role-column-header flex p-3 bg-emerald-400">
                    <div class="text-lg w-1/3">Token</div>
                    <div class="text-lg w-1/3">Floor</div>
                    <div class="text-lg w-1/3">Room</div>
                </div>
                <div class="role-column-data">
                </div>
            </div>
        `);
    });

    startMonitor();
});

roleSelectElement.append(startButtonElement);

const startMonitor = () => {
    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    let completedFetch = true;

    setInterval(async () => {

        if (!completedFetch) return;

        completedFetch = false;

        const response = await fetch(fetchUrl.monitorSocket + '/' + queues.join(',') + '/' + lastLoadedId + '/' + firstLoadedId, {
            headers,
            method: 'get',
        });
    
        if (!response.ok) {
            window.alert('Failed');
            return;
        }
    
        const responseData = await response.json();

        const {queue, ended} = responseData;

        if (queue.length > 0) {
            lastLoadedId = queue[queue.length - 1].id;
            firstLoadedId = queue[0].id;

            queue.forEach(queue => {
                $(`#role-column-${queue.role_id}`).append(`
                    <div data-id="${queue.id}" class="flex text-lg p-3">
                        <div class="w-1/3">${queue.token}</div>
                        <div class="w-1/3">${queue.floor}</div>
                        <div class="w-1/3">${queue.room}</div>
                    </div>
                `);
            });
        }

        if (ended.length > 0) {
            ended.forEach(ending => {
                $(`[data-id="${ending.id}"]`).remove();
            })
        }

        completedFetch = true;
    }, 1000);
}