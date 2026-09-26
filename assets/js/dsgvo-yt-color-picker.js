jQuery(function ($) {
    'use strict';

    var $settings = $('#dsgvo_yt_video_settings');
    if (!$settings.length) {
        return;
    }
    if ($.fn.wpColorPicker) {
        $settings.find('.wp-color-picker-field').wpColorPicker();
    }
    // Switching templates only hides the controls; it never clears saved colors.
    $('#dsgvo_yt_template').on('change', function () {
        var custom = this.value === 'custom';
        $('#dsgvo_yt_custom_colors').toggle(custom).attr('aria-hidden', custom ? 'false' : 'true');
    }).trigger('change');
});
