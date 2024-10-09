const fetchUrl = {
    currentToken: '/queues/current-token',
    forward: '/queues/forward',
    callNext: '/queues/call-next',
    recall: '/queues/recall',
    newTokenInQueue: '/queues/new-token-in-queue',
};

let currentToken = null;

const forwardTokenButtonElement = $('#forward-token');
const hideForwardModalButtonElement = $('#hide-forward-modal');
const nextTokenButtonElement = $('#next-token');
const recallButtonElement = $('#recall');

const roleListElement = $('#role-list');
const currentTokenElement = $('#current-token');
const forwardModalElement = $('#forward-modal');
const forwardModalBackdropElement = $('#forward-modal-backdrop');

const newTokenAlertAudioElement = new Audio('/audio/cartoon_notifier_prompt_message.mp3');
newTokenAlertAudioElement.loop = true;

$(document).ready(async function () {
    if (currentToken === null) {
        const headers = new Headers();
        headers.append('X-Requested-With', 'fetch');

        const response = await fetch(fetchUrl.currentToken, {
            headers,
            method: 'get',
        });

        if (!response.ok) {
            window.alert('Failed');
            return;
        }

        if (response.status === 204) {
            currentTokenElement.text('Empty');
            
            checkForNewTokenInQueue();

            return;
        }

        const responseData = await response.json();

        currentToken = responseData.token;

        currentTokenElement.text(responseData.token);
    }
});

window.forwardRoles.forEach(role => {

    const button = $('<button class="flex justify-center items-center w-full h-16 bg-emerald-400 rounded-md forward-token"></button>');
    button.append(role.name);

    button.on('click', async () => {
        if (currentToken === null) {
            window.alert('No token in progress');
            return;
        }

        const headers = new Headers();
        headers.append('X-Requested-With', 'fetch');

        const response = await fetch(fetchUrl.forward + '/' + role.id + '/' + currentToken, {
            headers,
            method: 'get',
        });

        if (!response.ok) {
            window.alert('Failed');
            return;
        }

        forwardModalElement.addClass('hidden');

        currentToken = null;
        currentTokenElement.text('');
    });

    roleListElement.append(button);

});

currentTokenElement.on('click', () => {
    if (!newTokenAlertAudioElement.paused) {
        newTokenAlertAudioElement.pause();
        currentTokenElement.text('NEW');
    }
});

nextTokenButtonElement.on('click', async () => {
    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    const response = await fetch(fetchUrl.callNext, {
        headers,
        method: 'get',
    });

    if (!response.ok) {
        window.alert('Failed');
        return;
    }

    if (response.status === 204) {
        currentTokenElement.text('Empty');
        checkForNewTokenInQueue(true);
        return;
    }

    const responseData = await response.json();

    newTokenAlertAudioElement.pause();

    currentToken = responseData.token;

    currentTokenElement.text(responseData.token);
});

forwardTokenButtonElement.on('click', () => {
    if (currentToken === null) {
        window.alert('No token in progress');
        return;
    }

    forwardModalElement.removeClass('hidden');
});

forwardModalBackdropElement.on('click', () => { forwardModalElement.addClass('hidden') });
hideForwardModalButtonElement.on('click', () => { forwardModalElement.addClass('hidden') });

recallButtonElement.on('click', async () => {
    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    const response = await fetch(fetchUrl.recall, {
        headers,
        method: 'get',
    });

    if (!response.ok) {
        window.alert('Failed');
    }
});

const checkForNewTokenInQueue = (playAlertAudio) => {
    let interval = setInterval(async () => {
        const headers = new Headers();
        headers.append('X-Requested-With', 'fetch');

        const response = await fetch(fetchUrl.newTokenInQueue, {
            headers,
            method: 'get',
        });

        if (!response.ok) {
            window.alert('Failed');
        }

        const responseData = await response.text();

        if (responseData === '1') {
            clearInterval(interval);

            
            if (playAlertAudio) {
                currentTokenElement.html('<div class="text-center">NEW<div class="text-sm mt-1">Click to mute</div></div>');
                newTokenAlertAudioElement.play();
            } else {
                currentTokenElement.text('NEW');
            }
        }
    }, 1000);
}