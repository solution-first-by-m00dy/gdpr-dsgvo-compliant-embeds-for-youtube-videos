jQuery(function ($) {
    // init color pickers
    $('.wp-color-picker-field').wpColorPicker();
    // toggle custom color fields
    $('#dsgvo_yt_template').on('change', function () {
        $('#dsgvo_yt_custom_colors').toggle(this.value === 'custom');
    });
});
