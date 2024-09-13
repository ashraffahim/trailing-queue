const slots = [];
const tempIds = [];

let newFromTime, newToTime, tempId, code;

const fromTimeInput = $('#from-time');
const toTimeInput = $('#to-time');
const durationInput = $('#duration');
const bufferInput = $('#buffer');
const durationTypeInput = $('#duration-type');
const bufferTypeInput = $('#buffer-type');
const generateSlots = $('#generate-slots');

const updateSlots = $('#update-slots');

fromTimeInput.val('01:00');
toTimeInput.val('09:00');
durationInput.val(1);
bufferInput.val(0.5);

generateSlots.on('click', function() {
    const fromTime = fromTimeInput.val();
    const toTime = toTimeInput.val();
    const duration = durationTypeInput.val() === 'hr' ? parseInt(durationInput.val() * 60) : parseInt(durationInput.val());
    const buffer = bufferTypeInput.val() === 'hr' ? parseInt(bufferInput.val() * 60) : parseInt(bufferInput.val());

    const fromTimestamp = (new Date()).setHours(...fromTime.split(':'));
    const toTimestamp = (new Date()).setHours(...toTime.split(':'));

    const numberOfSlots = Math.floor((((toTimestamp - fromTimestamp) / 60000) + buffer) / (duration + buffer));

    for (let day = 0; day < 7; day++) {

        slots[day] = [];

        for (let slot = 0; slot < numberOfSlots; slot++) {

            code = '';

            do {

                for (let i = 0 ; i < 6; i++) {
                    code += Math.floor(Math.random() * Math.floor(9)).toString();
                }

                tempId = Number(code);
            } while (tempIds.includes(tempId));

            tempIds.push(tempId);

            newFromTime = new Date();
            newFromTime.setTime(fromTimestamp + ((slot * (duration + (slot === 0 ? 0 : buffer))) * 60 * 1000));

            newToTime = new Date();
            newToTime.setTime(fromTimestamp + ((((slot + 1) * (duration + buffer)) - buffer) * 60 * 1000));

            slots[day][slot] = {
                tempId,
                day,
                from: newFromTime.getHours().toString().padStart(2, '0') + ':' + newFromTime.getMinutes().toString().padStart(2, '0'),
                to:  newToTime.getHours().toString().padStart(2, '0') + ':' + newToTime.getMinutes().toString().padStart(2, '0'),
                duration,
                buffer,
                isOpen: true
            };
        }
    }

    displaySlots();
});

const displaySlots = () => {
    slots.forEach((day, index) => {

        const dayLine = $(`#slot-${index}`);
        dayLine.html('');

        day.forEach(slot => {
            const slotBox = $('<div class="inline-flex flex-row items-center h-24 mx-2 px-3 py-1 border border-gray-200 rounded-md"></div>');
            const slotCol1 = $('<div class="flex flex-col"></div>');
            const slotCol2 = $('<div class="flex flex-col"></div>');

            const slotFromTimeInputLabel = $('<div class="text-xs text-gray-400">From</div>');
            const slotFromTimeInput = $('<div class="text-xs">' + slot.from + '</div>');
            const slotToTimeInputLabel = $('<div class="text-xs text-gray-400">To</div>');
            const slotToTimeInput = $('<div class="text-xs">' + slot.to + '</div>');

            const slotRemove = $('<button class="btn-danger-muted text-xs mt-0"><i class="fa fa-trash"></i></button>')
            const slotEdit = $('<button class="btn-muted text-xs mt-0"><i class="fa fa-pencil"></i></button>')
            const slotStatus = $('<button class="btn-muted text-xs mt-0"><i class="fa fa-circle-xmark"></i></button>')

            slotCol1
            .append(slotFromTimeInputLabel)
            .append(slotFromTimeInput)
            .append(slotToTimeInputLabel)
            .append(slotToTimeInput);

            slotCol2
            .append(slotRemove)
            .append(slotEdit)
            .append(slotStatus);

            slotBox
            .append(slotCol1)
            .append(slotCol2);

            dayLine
            .append(slotBox);
        });
    });
}

updateSlots.on('click', async function() {
    validateSlots();

    const csrf = {param: $('meta[name="csrf-param"]').attr('content'), token: $('meta[name="csrf-token"]').attr('content')};

    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    const body = new FormData();
    body.append('slots', JSON.stringify(slots));
    body.append(csrf.param, csrf.token);

    const response = await fetch(window.location.pathname, {
        headers,
        method: 'POST',
        body,
    });

    if (!response.ok) console.log(response);

    const responseData = await response.json();

    window.ur = responseData;
});

const validateSlots = () => {
    let hasCollision = false;

    slots.forEach(day => {
        day.forEach(slot => {
        });
    });
}