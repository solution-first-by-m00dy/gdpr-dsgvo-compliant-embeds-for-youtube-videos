(function ($) {
    'use strict';

    var consentCookieName = 'dsgvo_yt_consent';
    var consentCookieDays = 180;
    var messages = window.dsgvoYt || {};

    function hasConsentCookie() {
        return document.cookie.split(';').some(function (cookie) {
            return cookie.trim() === consentCookieName + '=1';
        });
    }

    function writeConsentCookie(remember) {
        var expires = new Date(remember ? Date.now() + consentCookieDays * 86400000 : 0);
        var cookie = consentCookieName + '=' + (remember ? '1' : '') + '; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
        if (window.location.protocol === 'https:') {
            cookie += '; Secure';
        }
        document.cookie = cookie;
    }

    function allowedVideoUrl(src) {
        if (/[\s\\\u0000-\u001f\u007f]/.test(src)) {
            return null;
        }
        var url;
        try {
            url = new URL(src);
        } catch (_) {
            return null;
        }
        var hosts = ['www.youtube.com', 'www.youtube-nocookie.com'];
        var authority = src.match(/^https:\/\/([^/?#]+)/i);
        // URL normalizes the explicit HTTPS default port 443 to an empty port.
        if (url.protocol !== 'https:' || url.username || url.password || url.port || !authority || hosts.indexOf(url.hostname) === -1) {
            return null;
        }
        var video = /^\/embed\/[A-Za-z0-9_-]{11}\/?$/.test(url.pathname) && !/^\/embed\/videoseries\/?$/.test(url.pathname);
        var playlist = /^\/embed\/videoseries\/?$/.test(url.pathname) && /^[A-Za-z0-9_-]+$/.test(url.searchParams.get('list') || '');
        var listType = url.searchParams.get('listType');
        var listEmbed = /^\/embed\/?$/.test(url.pathname) && (listType === 'playlist' || listType === 'user_uploads') && /^[A-Za-z0-9_-]+$/.test(url.searchParams.get('list') || '');
        return video || playlist || listEmbed ? url.href : null;
    }

    function copySafeLegacyStyles(source, target) {
        // Preserve harmless iframe layout from 1.0.1 without accepting CSS resources
        // or allowing an embed to position itself outside its existing container.
        var properties = ['width', 'height', 'min-width', 'max-width', 'min-height', 'max-height',
            'border', 'border-width', 'border-style', 'border-color', 'border-radius',
            'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
            'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
            'display', 'vertical-align', 'aspect-ratio', 'object-fit'];
        var functions = ['calc', 'min', 'max', 'clamp', 'rgb', 'rgba', 'hsl', 'hsla'];
        var css = source.getAttribute('style') || '';
        if (css.length > 4096) {
            return;
        }
        css.split(';').forEach(function (declaration) {
            var colon = declaration.indexOf(':');
            if (colon < 1) {
                return;
            }
            var property = declaration.slice(0, colon).trim().toLowerCase();
            var value = declaration.slice(colon + 1).trim().replace(/\s+/g, ' ');
            if (properties.indexOf(property) === -1 || !value || value.length > 512 ||
                !/^[A-Za-z0-9#.,%() +*/!\-]+$/.test(value) || /\/\*|\*\//.test(value)) {
                return;
            }
            var usedFunctions = value.match(/[a-z][a-z0-9-]*\s*\(/gi) || [];
            if (usedFunctions.some(function (name) { return functions.indexOf(name.replace(/\s*\($/, '').toLowerCase()) === -1; })) {
                return;
            }
            var important = /\s*!important\s*$/i.test(value);
            value = value.replace(/\s*!important\s*$/i, '');
            if (value.indexOf('!') !== -1) {
                return;
            }
            var probe = document.createElement('span').style;
            probe.setProperty(property, value, important ? 'important' : '');
            if (probe.getPropertyValue(property)) {
                target.style.setProperty(property, probe.getPropertyValue(property), probe.getPropertyPriority(property));
            }
        });
    }

    function showError($container) {
        if (!$container.find('.dsgvo-yt-error').length) {
            $('<p>', { 'class': 'dsgvo-yt-error', role: 'alert' })
                .text(messages.errorText || 'This video could not be loaded. Please contact the website owner.')
                .appendTo($container);
        }
    }

    function loadOverlay($container) {
        if (!$container.length || $container.data('dsgvoYtLoaded')) {
            return false;
        }
        try {
            var b64 = ($container.attr('data-iframe') || '').replace(/\s+/g, '');
            if (!b64 || b64.length > 131072) {
                throw new Error('Invalid video data');
            }
            var raw = window.atob(b64);
            var bytes = Uint8Array.from(raw, function (character) { return character.charCodeAt(0); });
            var html = window.TextDecoder ? new TextDecoder('utf-8', { fatal: true }).decode(bytes) : raw;
            // Template contents are inert: discarded images, scripts and frames never load.
            var template = document.createElement('template');
            template.innerHTML = html;
            var iframe = template.content.querySelector('iframe');
            var src = iframe && allowedVideoUrl(iframe.getAttribute('src') || '');
            if (!src) {
                throw new Error('Unsupported video URL');
            }

            // Rebuild from an allowlist; active attributes are never copied.
            var $safeIframe = $('<iframe>', {
                src: src,
                frameborder: '0',
                allow: 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
                title: iframe.getAttribute('title') || messages.frameTitle || 'YouTube video player',
                'class': 'dsgvo-yt-frame',
                allowfullscreen: '',
                loading: 'lazy',
                referrerpolicy: 'strict-origin-when-cross-origin',
                sandbox: 'allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-top-navigation-by-user-activation'
            });
            // Use attributes, not jQuery's width()/height() setters: inline pixels
            // would override the existing stylesheet's 100% player dimensions.
            $safeIframe.attr({
                width: /^\d{1,6}%?$/.test(iframe.getAttribute('width') || '') ? iframe.getAttribute('width') : '600',
                height: /^\d{1,6}%?$/.test(iframe.getAttribute('height') || '') ? iframe.getAttribute('height') : '450'
            });
            copySafeLegacyStyles(iframe, $safeIframe[0]);
            $container.find('.dsgvo-yt-error').remove();
            $container.data('dsgvoYtOriginal', $container.contents().detach());
            $container.data('dsgvoYtLoaded', true).addClass('dsgvo-yt-loaded').append($safeIframe);
            $('.dsgvo-yt-reset-status').empty();
            if ($container.attr('data-show-reset') === '1') {
                var $revoke = $('<button>', { type: 'button', 'class': 'dsgvo-yt-revoke-btn' })
                    .text(messages.revokeText || 'Unload videos and reset choice');
                $container.append($('<div>', { 'class': 'dsgvo-yt-controls' }).append($revoke));
            }
            return true;
        } catch (_) {
            showError($container);
            return false;
        }
    }

    function getTargetOverlays($container) {
        return $container.attr('data-load-all') === '1' ? $('.dsgvo-yt-overlay[data-load-all="1"]') : $container;
    }

    $(document).on('click', '.dsgvo-yt-load-btn', function (event) {
        event.preventDefault();
        var $container = $(this).closest('.dsgvo-yt-overlay');
        var remember = $container.attr('data-remember-enabled') === '1' && $container.find('.dsgvo-yt-remember-checkbox').is(':checked');
        var loaded = false;
        getTargetOverlays($container).each(function () {
            loaded = loadOverlay($(this)) || loaded;
        });
        if (remember && loaded) {
            writeConsentCookie(true);
        }
    });

    function resetChoice() {
        writeConsentCookie(false);
        $('.dsgvo-yt-overlay').each(function () {
            var $container = $(this);
            if ($container.data('dsgvoYtLoaded')) {
                var original = $container.data('dsgvoYtOriginal');
                $container.empty().append(original).removeClass('dsgvo-yt-loaded');
                $container.removeData('dsgvoYtLoaded').removeData('dsgvoYtOriginal');
            }
            $container.find('.dsgvo-yt-remember-checkbox').prop('checked', false);
        });
        $('.dsgvo-yt-reset-status').text(messages.resetText || 'Your choice has been reset. Videos will load only after you consent again.');
    }

    $(document).on('click', '.dsgvo-yt-revoke-btn', function (event) {
        event.preventDefault();
        var $current = $(this).closest('.dsgvo-yt-overlay');
        resetChoice();
        $current.find('.dsgvo-yt-load-btn').trigger('focus');
    });

    $(document).on('click', '.dsgvo-yt-reset-btn', function (event) {
        event.preventDefault();
        resetChoice();
    });

    $(document).on('change', '.dsgvo-yt-remember-checkbox', function () {
        $('.dsgvo-yt-reset-status').empty();
    });

    $(function () {
        if (hasConsentCookie()) {
            $('.dsgvo-yt-overlay[data-remember-enabled="1"]').each(function () {
                loadOverlay($(this));
            });
        }
    });
})(jQuery);
