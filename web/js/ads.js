const fetchUrl = {
    currentToken: '/ads/upload',
};

const csrfParam = $('meta[name="csrf-param"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

const fileInputElement = $('#file-input');

fileInputElement.on('change', async () => {
    const files = fileInputElement[0].files;

    if (files.length === 0) return;


    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    const formData = new FormData();

    formData.append(csrfParam, csrfToken);

    for (file of files) {
        formData.append('AdsUploadForm[files][]', file);
    }

    const response = await fetch('/ads/upload', {
            headers,
            method: 'post',
            body: formData
        }
    );

    fileInputElement.val('');

    if (!response.ok) {
        window.alert('Upload failed!');
    }

    await response.text();

    window.location.reload();
});

$(document).on('drag', () => { });