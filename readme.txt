=== GDPR-DSGVO compliant Embeds for YouTube Videos ===
Contributors: solutionfirst
Donate link: https://www.paypal.com/donate/?hosted_button_id=CUPZTPGSAHNKY
Tags: youtube, video, gdpr, privacy, shortcode
Requires at least: 6.2
Tested up to: 7.1
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Load YouTube embeds after consent, with per-video styles, text sizes, optional remembered choices, grouped loading and reset controls.

== Description ==

Create unlimited YouTube embeds in WordPress and insert them with `[dsgvo_video id="123"]`. A local overlay appears before the configured player is loaded. The plugin is free, requires no license or API key, and adds no advertising or developer tracking. YouTube's own player and advertising remain controlled by YouTube.

Published by [Tsambasis & Tsambasis](https://tsambasis.net/).
[Live demonstration — Tsambasis & Tsambasis](https://plugin-demo.m00dy.org/live-demonstration/).

* Separate iframe input, settings and shortcode for each video.
* Light, dark and custom colors; rounded or square load buttons.
* Optional overlay message and configurable privacy notice/link.
* Five independent font sizes: button, message, privacy text, privacy link and remember label. Empty fields preserve the existing theme/plugin styling.
* Existing dimensions and percentage-based aspect-ratio behavior retained.
* Optional grouped loading for participating videos on the current page.
* Optional remember checkbox, with the classic design by default or a larger modern design selected per video.
* A separate reset shortcode, plus an optional inline reset control.
* English interface text and a bundled German translation, following WordPress's language.

New options are opt-in. Existing videos do not automatically gain messages, remembered consent, grouped loading, modern checkboxes or reset buttons. Saved custom text remains unchanged.

= Setup and appearance =

Open Videos > Add New Video, paste the complete YouTube iframe from Share > Embed, choose settings and publish. Then place its shortcode in a Shortcode block. Supported embed hosts are `www.youtube.com` and `www.youtube-nocookie.com`, using HTTPS. A normal watch URL or youtu.be sharing link is not embed code.

Leave a font-size field empty to preserve the existing appearance. Supported font units include px, em, rem and %. Choose enough height for the overlay message and controls on mobile. Percentage heights retain the original aspect-ratio behavior: with full width, 56.25% is a common 16:9 setting. The plugin does not automatically enlarge existing videos.

Under Checkbox design, choose Modern design (larger checkbox) to enable the optional 22px checkbox and styled label card. The remember option must also be enabled. Existing design remains the default.

= Consent, grouped loading and the optional cookie =

Without a previously remembered choice, the player is inserted after the load button is selected. Without the remember checkbox, that action creates no new plugin consent cookie. An existing saved choice is not cleared merely by leaving a checkbox unchecked.

Checking the remember option before loading saves `dsgvo_yt_consent=1` for up to 180 days once a valid player is inserted. This persistent first-party cookie uses path `/`, SameSite=Lax and Secure on HTTPS. It is not a session cookie that is guaranteed to disappear when the browser closes. Browser settings may block or delete it earlier. Only videos with remembering enabled load automatically on later page views. This YouTube choice is separate from the Google Maps plugin's cookie and does not consent to maps.

The checkbox is optional and initially unchecked. Explain its website-wide effect in your notice. The cookie stores a loading preference, not a server-side consent audit record.

Grouped loading is configured per video. Clicking a participating video also loads the other participating videos on that page; videos outside the group remain separate. Describe that scope in your button or message before requesting consent. Inserting the player does not guarantee that YouTube will play the video: a further play action or provider restrictions may apply.

= Reset options =

Place `[dsgvo_video_reset]` anywhere in page content, including a page without videos. Customize its label with `[dsgvo_video_reset text="Reset my video choice"]`. This separate button remains visible and enabled, clears the YouTube consent cookie, unloads current-page plugin videos and unchecks their remember boxes. A status message confirms the action; keyboard focus remains on the button.

For a button inside a loaded video, explicitly use `[dsgvo_video id="123" show_reset="true"]`. This optional inline control uses 64px of the configured video height. An ordinary video shortcode adds no reset control. Use the separate button to keep the full player area.

Reset does not unload players already open in other tabs or undo information already sent to YouTube/Google. It does not clear the separate Google Maps choice. Visitors can also remove this website's consent cookie through browser settings.

= External service: YouTube / Google =

After loading, the browser connects directly to YouTube/Google and may request additional player/media resources. Google receives connection and device information, including IP address and the website origin through the `strict-origin-when-cross-origin` referrer policy. It may process cookies or account-related data. The page path and query are not included in that cross-origin referrer.

The plugin supports YouTube's privacy-enhanced `www.youtube-nocookie.com` embeds. This does not mean no Google connection, no data processing or no advertising. YouTube may still show non-personalized ads in that mode. See [YouTube's official embedding guidance](https://support.google.com/youtube/answer/171780?hl=en).

* [Google Privacy Policy](https://policies.google.com/privacy)
* [YouTube Terms of Service](https://www.youtube.com/t/terms)
* [YouTube API Services Terms](https://developers.google.com/youtube/terms/api-services-terms-of-service)

The local overlay delays only the configured plugin player. It does not block requests made by your theme, other plugins, separately embedded players or other services. The plugin name is not a guarantee of GDPR/DSGVO compliance for your complete website. You remain responsible for valid consent, notices, withdrawal options and your full data flows. This is independent software, not an official YouTube product or legal advice.

== Installation ==

1. Install the release ZIP under Plugins > Add New > Upload Plugin and activate it. For manual installation, use the `gdpr-dsgvo-compliant-embeds-for-youtube-videos` folder inside `wp-content/plugins/`.
2. Create and publish a video under Videos > Add New Video.
3. Insert `[dsgvo_video id="123"]`, replacing 123 with the ID shown in its editor.
4. Enable additional text, remembering, grouping or modern styling only where desired.
5. Add a reset shortcode where visitors can find it, update your privacy notice and test desktop/mobile behavior.

JavaScript is required for player loading and reset controls. Use the installable release ZIP; the separate WordPress.org assets ZIP contains directory images.

== Frequently Asked Questions ==

= Is the plugin free and ad-free? =

The plugin is free, has no video limit and adds no ads. YouTube may show its own advertising. No API key is needed for supported iframe embeds.

= Why does a video not play after loading? =

The owner may prohibit embedding, or the video may be private, removed or age-restricted. Network/privacy blockers can also interfere. Consent makes the player available; it does not override YouTube's restrictions or autoplay rules.

= Will existing videos change appearance? =

New font fields start empty and new features are disabled by default. Existing layout and safe stored settings are retained. Explicitly enabling modern checkboxes or inline reset controls changes those controls as described above.

= Where can I get help? =

Contact [Tsambasis & Tsambasis](https://tsambasis.net/). The established [plugin information page](https://solutionfirst.m00dy.org/wp-plugin/) and [live demonstration](https://plugin-demo.m00dy.org/live-demonstration/) retain their URLs. `solutionfirst` remains the WordPress.org contributor account.

== Screenshots ==

1. Video list in WordPress.
2. Iframe input, separate-reset help, button text, font size and shape.
3. Custom design colors and video dimensions.
4. Privacy text/link typography and optional overlay message.
5. Grouped loading, remember options and explicitly selected modern checkbox design.
6. Mobile overlay with the optional modern checkbox enabled.
7. Light, dark and custom overlays with modern checkbox design enabled.
8. Loaded YouTube player with the inline reset explicitly enabled by show_reset="true".
9. Separate reset button inserted with [dsgvo_video_reset], including status feedback.

== Changelog ==

= 1.1.0 =
* Added optional overlay message and five font-size controls; empty sizes retain existing appearance.
* Added per-video grouped loading and optional 180-day remembered choices, separate from Maps consent.
* Added classic/modern remember-checkbox designs and explicit inline/separate reset controls.
* Improved iframe/CSS validation, permission checks and preservation of existing settings/layout.
* Updated publisher details, German translations, documentation and actual screenshots.
* Requires WordPress 6.2 or later.

= 1.0.1 =
* Updated banners and plugin information.

= 1.0.0 =
* Initial release with consent-based YouTube iframe embeds.
