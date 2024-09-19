let queues = [null, null, null];
let lastLoadedId = 0;
let firstLoadedId = 0;
let calledTokens = [];
let endedTokens = [];
let recalledTokens = {};
let adElements = [];

const fixedAds = [
    '/images/bangladesh-embassy-ad.png',
    '/images/amaar-clinic-ad.png',
    '/images/amc-ad.png',
];
const imageExtentions = ['jpg', 'png', 'jpeg', 'webp', 'gif', 'jfif'];
const videoExtentions = ['mp4', 'mov', 'wmv', 'avi', 'webm', 'mpeg-2'];
const fetchUrl = {
    monitorSocket: '/queues/monitor-socket'
};

const roleSelectElement = $('#role-select');
const roleColumnsElement = $('#role-columns');
const queueContainerElement = $('#queue-container');
const queueElement = $('#queue');
const adsElement = $('#ads');

const roleModalElement = $('#role-modal');

const startButtonElement = $('<button class="text-lg">START</button>');

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
    <div class="flex p-3 text-white" style="background-color: #069;">
        <div class="text-4xl w-1/3">Token</div>
        <div class="text-4xl w-1/3">Floor</div>
        <div class="text-4xl w-1/3">Count/Room</div>
    </div>
`);

// Bangladesh embassy logo in first slide
fixedAds.forEach((fixedAd, index) => {
    const embassySlideLogoImageElement = new Image();
    embassySlideLogoImageElement.src = fixedAd;
    embassySlideLogoImageElement.style.maxWidth = '100%';
    embassySlideLogoImageElement.style.maxHeight = '100%';
    if (index > 0) embassySlideLogoImageElement.classList.add('hidden');
    
    adElements.push(embassySlideLogoImageElement);
    adsElement.append(embassySlideLogoImageElement);
});

window.ads.forEach(ad => {
    const adNameParts = ad.split('.');
    const adExt = adNameParts[adNameParts.length - 1].toLowerCase();
 
    if (imageExtentions.includes(adExt)) {
        const image = new Image();
        image.src = ad;
        image.style.maxWidth = '100%';
        image.style.maxHeight = '100%';
        image.classList.add('hidden');

        adElements.push(image);

        adsElement.append(image);
    } else if (videoExtentions.includes(adExt)) {
        const video = $('<video></video>');
        video.attr('src', ad);
        video.addClass('hidden');
        
        adElements.push(video[0]);
        adsElement.append(video);
    }
});

startButtonElement.on('click', () => {
    roleModalElement.addClass('hidden');

    queues.forEach(role => {
        roleColumnsElement.append(`
            <div id="role-column-${role}" class="flex flex-col flex-auto min-h-screen border border-emerald-100">
                <div class="role-column-header flex p-3 text-white" style="background-color: #069;">
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
    startAdsSlide();
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
                    if (queue.status === 1) {
                        calledTokens.push(queue.id);
                        insertNewRowInQueue(queue);
                        textToSpeech('Token number, ' + queue.token.split('').join(', ') + ', in, counter, ' + queue.room.split('').join(', '));
                    } else if (queue.status === 2) {
                        recalledTokens[queue.id] = queue.recall_count;
                        insertNewRowInQueue(queue);
                        textToSpeech('Recalling, ' + queue.token.split('').join(', ') + ', in, counter, ' + queue.room.split('').join(', '));
                    } else {
                        insertUpcomingRowInQueue(queue);
                    }
                } catch (e) {
                    console.log(e);
                }
            });
        }

        if (called.length > 0) {
            called.forEach(token => {
                if (!calledTokens.includes(token.id)) {
                    calledTokens.push(token.id);

                    insertNewRowInQueue(token);

                    textToSpeech('Token number, ' + token.token.split('').join(', ') + ', in, counter, ' + token.room.split('').join(', '));

                    roleColumnsElement.find(`[data-id="${token.id}"]`).remove();
                }
            })
        }
        if (recalled.length > 0) {
            recalled.forEach(token => {
                if (!recalledTokens.hasOwnProperty(token.id) || recalledTokens[token.id] < token.recall_count) {
                    recalledTokens[token.id] = token.recall_count;

                    textToSpeech('Recalling, ' + token.token.split('').join(', ') + ', in, counter, ' + token.room.split('').join(', '));

                    highlightRecall(token.id);

                    roleColumnsElement.find(`[data-id="${token.id}"]`).remove();
                }
            })
        }
        if (ended.length > 0) {
            ended.forEach(ending => {
                if (!endedTokens.includes(ending.id)) {
                    endedTokens.push(ending.id);
                    $(`[data-id="${ending.id}"]`).remove();
                }
            })
        }

        completedFetch = true;
    }, 1000);
}

const startAdsSlide = () => {
    let interval = null;
    let slideIndex = 0;

    const slideDelay = 5000;

    const slideProcess = () => {
        let prevSlideIndex = 0;

        if (!adElements[slideIndex]) {
            slideIndex = 0;
            prevSlideIndex = 0;
        }

        if (slideIndex !== 0) {
            prevSlideIndex = slideIndex - 1;
        } else if (adElements.length > 1) {
            prevSlideIndex = adElements.length - 1;
        }

        adElements[prevSlideIndex].classList.add('hidden');

        adElements[slideIndex].classList.remove('hidden');

        if (adElements[slideIndex].tagName === 'VIDEO') {
            clearInterval(interval);

            if (adElements[slideIndex].paused) {
                adElements[slideIndex].play();
            }

            adElements[slideIndex].onended = () => {
                interval = setInterval(slideProcess, slideDelay);
            }

        }

        slideIndex++;
    }

    if (adElements.length > 0) {
        interval = setInterval(slideProcess, slideDelay);
    }
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
    const row = $(`<div data-id="${token.id}" class="text-3xl font-bold row-in-queue new-row-in-queue"></div>`);
    
    row.append(`
        <div class="w-1/3">${token.token}</div>
        <div class="w-1/3">${token.floor}</div>
        <div class="w-1/3">${token.room}</div>
    `);

    queueElement.prepend(row);

    setTimeout(() => {
        row.removeClass('new-row-in-queue');
    }, 4000);
}

const insertUpcomingRowInQueue = (queue) => {
    $(`#role-column-${queue.role_id}`).append(`
        <div data-id="${queue.id}" class="text-lg row-in-queue">
            <div class="w-1/3">${queue.token}</div>
            <div class="w-1/3">${queue.floor}</div>
            <div class="w-1/3">${queue.room}</div>
        </div>
    `);
}

const highlightRecall = id => {
    const row = $(`[data-id="${id}"]`).addClass('new-row-in-queue');
    
    setTimeout(() => {
        row.removeClass('new-row-in-queue');
    }, 4000);
}