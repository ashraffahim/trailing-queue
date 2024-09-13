function selectClassic(options) {

    if (window.selectClassicInitialized) return;

    window.selectClassicInitialized = true;

    const defaultOptions = {
        nameDataAttr: 'name',
        valueDataAttr: 'value',
        selectSelector: '.select-classic',
        valueSelector: '.select-value',
        optionSelector: '.select-option',
    };

    const finalOptions = {...defaultOptions, ...options};

    $(finalOptions.selectSelector).each(function() {
        $(this).val($(this).find(`${finalOptions.valueSelector} input[name="${$(this).data(finalOptions.nameDataAttr)}"]`).val());
    });

    $(document).on('click', function(evt) {
        let item = [];

        if ($(evt.target).is(finalOptions.optionSelector)) item = $(evt.target);
        else item = $(evt.target).parents(finalOptions.optionSelector);

        if (item.length !== 0) {
            
            // const item = $(finalOptions.optionSelector);
            const select = item.parents(finalOptions.selectSelector);

            const inputName = select.data(finalOptions.nameDataAttr);
            const inputValue = item.data(finalOptions.valueDataAttr);

            select.parents('.has-error').removeClass('has-error');
            select.removeClass('open');

            select.val(inputValue);
            select.trigger('change');
            select.blur();

            item.parents(finalOptions.selectSelector)
            .find(finalOptions.valueSelector)
            .html(
                item.html()
                + '<input type="hidden" name="' + inputName + '" value="' + inputValue + '">'
            );

        } else {
            let select = [];

            if ($(evt.target).is(finalOptions.selectSelector)) select = $(evt.target);
            else select = $(evt.target).parents(finalOptions.selectSelector);

            if (select.length !== 0) {
                $(finalOptions.selectSelector).removeClass('open');
                $(select).addClass('open');
            }
        }
    });

}