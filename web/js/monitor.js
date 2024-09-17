let queues = [null, null, null];
let lastLoadedId = 0;
let firstLoadedId = 0;
let calledTokens = [];
let recalledTokens = {};

const fetchUrl = {
    monitorSocket: '/queues/monitor-socket'
};

const roleSelectElement = $('#role-select');
const roleColumnsElement = $('#role-columns');
const queueContainerElement = $('#queue-container');
const queueElement = $('#queue');

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

queueContainerElement.prepend(`
    <div class="flex p-3 bg-emerald-400">
        <div class="text-4xl w-1/3">Token</div>
        <div class="text-4xl w-1/3">Floor</div>
        <div class="text-4xl w-1/3">Room</div>
    </div>
`);

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

if (!('speechSynthesis' in window)) {
    alert('Your browser is not supported. If google chrome, please upgrade!!');
}

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

        const { queue, called, recalled, ended } = responseData;

        if (queue.length > 0) {
            lastLoadedId = queue[queue.length - 1].id;
            if (firstLoadedId === 0) {
                firstLoadedId = queue[0].id;
            }

            queue.forEach(queue => {
                try {
                    $(`#role-column-${queue.role_id}`).append(`
                        <div data-id="${queue.id}" class="flex text-lg p-3">
                            <div class="w-1/3">${queue.token}</div>
                            <div class="w-1/3">${queue.floor}</div>
                            <div class="w-1/3">${queue.room}</div>
                        </div>
                    `);

                    if (queue.status === 1) {
                        calledTokens.push(queue.token);
                        textToSpeech('Token number, ' + queue.token.split('').join(', ') + ', in, counter, ' + queue.room.split('').join(', '));
                    } else if (queue.status === 2) {
                        recalledTokens[queue.token] = queue.recall_count;
                        textToSpeech('Recalling, ' + queue.token.split('').join(', ') + ', in, counter, ' + queue.room.split('').join(', '));
                    }
                } catch (e) {
                    console.log(e);
                }
            });
        }

        if (called.length > 0) {
            called.forEach(token => {
                if (!calledTokens.includes(token.token)) {
                    calledTokens.push(token.token);

                    insertNewRowInQueue(token);

                    textToSpeech('Token number, ' + token.token.split('').join(', ') + ', in, counter, ' + token.room.split('').join(', '));
                }
            })
        }
        if (recalled.length > 0) {
            recalled.forEach(token => {
                if (!recalledTokens.hasOwnProperty(token.token) || recalledTokens[token.token] < token.recall_count) {
                    recalledTokens[token.token] = token.recall_count;

                    textToSpeech('Recalling, ' + token.token.split('').join(', ') + ', in, counter, ' + token.room.split('').join(', '));
                }
            })
        }
        if (ended.length > 0) {
            ended.forEach(ending => {
                $(`[data-id="${ending.id}"]`).remove();
            })
        }

        completedFetch = true;
    }, 1000);
}

const textToSpeech = speak => {
    const msg = new SpeechSynthesisUtterance();
    const voices = window.speechSynthesis.getVoices();
    msg.voice = voices[5]; // Note: some voices don't support altering params
    msg.voiceURI = 'native';
    msg.volume = 1; // 0 to 1
    msg.rate = 1; // 0.1 to 10
    msg.pitch = 1; //0 to 2
    msg.text = speak;
    msg.lang = 'en-US';
    window.speechSynthesis.speak(msg);
}

const insertNewRowInQueue = (token) => {
    queueElement.prepend(`
        <div data-id="${token.id}" class="flex text-lg p-3">
            <div class="w-1/3">${token.token}</div>
            <div class="w-1/3">${token.floor}</div>
            <div class="w-1/3">${token.room}</div>
        </div>
    `);
}