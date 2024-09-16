let queues = [null, null, null];

const fetchUrl = {
    monitor: '/queues/monitor-socket'
};

const roleSelectElement = $('#role-select');
const roleColumnsElement = $('#role-columns');

const roleModalElement = $('#role-modal');

queues.forEach((num, index) => {
    const select = $('<select class="text-lg"></select>');

    window.queueRoles.forEach(role => {
        select.append('<option value="' + role.id + '">' + role.name + '</option>');
    });

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
});

roleSelectElement.append(startButtonElement);

const startMonitor = () => {
    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    let completedFetch = true;

    setInterval(async () => {

        if (!completedFetch) return;

        completedFetch = false;

        const response = await fetch(fetchUrl.monitor + '/' + queues.join(','), {
            headers,
            method: 'get',
        });
    
        if (!response.ok) {
            window.alert('Failed');
            return;
        }
    
        const responseData = await response.json();

        completedFetch = true;
    }, 1000);
}