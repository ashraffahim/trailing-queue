$(document).on('click', evt => {
    const closeOnBlurElements = $('.close-on-blur.open');

    if (
        closeOnBlurElements.length > 0
        && !$(evt.target).is('.close-on-blur')
        && $(evt.target).parents('.close-on-blur').length === 0
    ) {
        $('.close-on-blur').removeClass('open');
    }
});