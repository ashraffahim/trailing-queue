const imageDropbox = $('#image-input-dropbox');

let [uploadedFiles, uploadFiles] = [[], (files) => {
    uploadedFiles = files;
    updateUploadedFileList();
}];

$(document).on('dragenter', evt => {
    if ($(evt.target).is('#image-input')) {
        imageDropbox.removeClass('border-gray-200 border-dashed');
        imageDropbox.addClass('border-indigo-600 border-solid');
    } else if (!imageDropbox.is('.border-indigo-600.border-dashed')) {
        imageDropbox.removeClass('border-gray-200 border-solid');
        imageDropbox.addClass('border-indigo-600 border-dashed');
    }
});

$(document).on('dragend drop', evt => {
    if (!imageDropbox.is('.border-gray-200.border-dashed')) {
        imageDropbox.removeClass('border-indigo-600 border-solid');
        imageDropbox.addClass('border-gray-200 border-dashed');
    }
});

$('#image-input').on('change', function() {

    const files = Array.from(this.files);

    for (index in files) {
        files[index].url = URL.createObjectURL(files[index]);
    }

    uploadFiles([...uploadedFiles, ...files]);

    this.value = '';

    $('#upload-image').prop('disabled', false);
});

$(document).on('blur', '.uploaded-file-name', function() {
    const index = $(this).data('index');
    const name = $(this).val();

    changeUploadedFileName(index, name);
});

$('#media-files-form').on('submit', async function(e) {
    e.preventDefault();

    $('#upload-image').prop('disabled', true);

    const headers = new Headers();
    headers.append('X-Requested-With', 'fetch');

    const formData = new FormData(this);
    uploadedFiles.forEach((file, index) => {
        formData.append('file[' + index + ']', file);
    });

    try {
        const response = await fetch($(this).attr('action'), {
            headers,
            method: 'POST',
            body: formData
        });
    
        if (!response.ok) {
            throw new Error(await response.text());
        }

        window.location.reload();
    } catch(e) {
        console.log(e);
    }
});

const updateUploadedFileList = () => {
    $('#uploaded-images').html('');

    uploadedFiles.forEach((file, index) => {
        const fileNameParts = file.name.split('.');
        const fileExt = fileNameParts.splice(fileNameParts.length - 1, 1);

        $('#uploaded-images').append(
            '<li>'
                + '<div>'
                    + '<img src="' + file.url + '" class="object-contain rounded-sm">'
                    + '<div>'
                        + '<p><input type="text" value="' + fileNameParts.join('.') + '" class="uploaded-file-name" data-index="' + index + '"></p>'
                        + '<p>' + fileExt[0].toUpperCase() + '</p>'
                    + '</div>'
                + '</div>'
                + '<div>'
                + '</div>'
            + '</li>'
        );
    });
}

const changeUploadedFileName = (index, name) => {
    const newUploadedFiles = uploadedFiles;
    const oldFileNameParts = uploadedFiles[index].name.split('.');
    const objUrl = uploadedFiles[index].url;

    newUploadedFiles[index] = new File([uploadedFiles[index]], name + '.' + oldFileNameParts[oldFileNameParts.length - 1]);
    newUploadedFiles[index].url = objUrl;

    uploadFiles(newUploadedFiles);
}