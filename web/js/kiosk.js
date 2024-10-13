const fetchUrl = {
    generateToken: '/queues/generate',
    printRequest: window.printRequestURL,
};

const roleListElement = $('#role-list');
const tokenModalElement = $('#token-modal');
const tokenModalBackdropElement = $('#token-modal-backdrop');
const tokenPrintElement = $('#token-print');

window.tokenRoles.forEach(role => {

    const button = $('<button class="sm:max-w-xl w-full text-4xl uppercase py-6 m-0 bg-emerald-400 rounded-md generate-token"></button>');
    button.append(role.name);

    button.on('click', async () => {
        const headers = new Headers();
        headers.append('X-Requested-With', 'fetch');

        const response = await fetch(fetchUrl.generateToken + '/' + role.id, {
            headers,
            method: 'get',
        });

        if (!response.ok) {
            if (response.status >= 400 && response.status < 500) {
                const responseError = await response.json();
                window.alert(responseError.message);
            } else window.alert('Failed');
            return;
        }

        const responseData = await response.json();

        sendPrintRequest([
            { size: 24.0, text: responseData.token },
            { size: 12.0, text: 'Level - ' + responseData.floor + ', Room - ' + responseData.room },
            { size: 12.0, text: responseData.date + ' ' + responseData.time },
            { size: 10.0, text: (responseData.currentToken != null ? ("Now serving " + responseData.currentToken) : "") },
            { size: 10.0, text: "Please take a seat" },
            { size: 8.0, text: "Sponsored by" },
            { size: 12.0, text: "Amaar Clinic" },
            { size: 8.0, text: "IT solutions by" },
            { size: 12.0, text: "www.almaidanae.com" }
        ]);

        tokenPrintElement.html('');
        tokenPrintElement.append(`
            <button id="hide-token-modal" class="relative flex justify-center items-center w-full text-red-600 rounded-md">Close</button>
            <div class="text-6xl font-black">${responseData.token}</div>
            <div class="text-lg">Level - ${responseData.floor}, Room - ${responseData.room}</div>
            <div class="text-sm">${responseData.date} ${responseData.time}</div>
        `);

        $('#hide-token-modal').on('click', () => { tokenModalElement.addClass('hidden') });

        tokenModalElement.removeClass('hidden');
        setTimeout(() => {
            tokenModalElement.addClass('hidden');
        }, 3000);
    });

    roleListElement.append(button);

});

tokenModalBackdropElement.on('click', () => { tokenModalElement.addClass('hidden') });

const sendPrintRequest = async data => {
    const headers = new Headers();
    headers.append("Content-Type", "application/json");

    const requestOptions = {
        method: "POST",
        headers,
        body: JSON.stringify(data),
        redirect: "follow"
    };

    await fetch(fetchUrl.printRequest, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.error(error));
}