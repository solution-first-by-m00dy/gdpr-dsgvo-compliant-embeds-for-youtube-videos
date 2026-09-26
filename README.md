# GDPR-DSGVO compliant Embeds for YouTube Videos

Consent-based YouTube embeds for WordPress, published by [Tsambasis & Tsambasis](https://tsambasis.net/).

Version **1.1.0** · WordPress **6.2+** · PHP **7.4+**

Create unlimited videos with local overlays and place them using `[dsgvo_video id="123"]`. The plugin is free, requires no API key or license, and adds no advertising or developer tracking. YouTube's own advertising and data processing are outside the plugin's control.

## Setup

1. Install the release ZIP and open **Videos → Add New Video**.
2. Paste the complete iframe from **YouTube → Share → Embed**. Supported HTTPS hosts are `www.youtube.com` and `www.youtube-nocookie.com`.
3. Choose appearance/privacy settings and publish.
4. Insert the video's shortcode into a Shortcode block.
5. Add your notices and a discoverable reset control; test desktop and mobile.

A watch-page or youtu.be link is not iframe embed code. JavaScript is required to load and reset players. Loading the player does not override YouTube restrictions or guarantee autoplay.

## Optional controls in 1.1.0

- Five font sizes: button, message, privacy text, privacy link and remember label. **Leave a field empty to keep the previous theme/plugin styling.**
- An overlay message between the load button and privacy notice.
- Grouped loading for participating videos on the same page.
- Optional remembered consent with classic or modern checkbox design. Modern is an explicit per-video choice with a 22px checkbox and styled label card.
- A separate reset button anywhere in page content, or an explicitly enabled inline reset.

Existing IDs, shortcodes and compatible dimensions/styles remain intact. New features start disabled, and existing videos are not automatically restyled. Percentage-height settings retain the former aspect-ratio behavior; choose enough room for optional overlay text and controls.

## Remembering and resetting

Without a prior remembered choice, a click inserts the player. Loading without checking Remember creates no new plugin consent cookie. A previously saved choice is retained until reset, expiry or browser removal.

Remembering creates the first-party `dsgvo_yt_consent=1` cookie for up to **180 days**, with path `/`, SameSite=Lax and Secure on HTTPS. Only videos offering this option load automatically later. This is persistent storage, not a promise to forget at browser close. It is independent of the Google Maps plugin's cookie and is not a server-side consent audit record.

Place a separate button in any page content, including a privacy page without a video:

```text
[dsgvo_video_reset text="Reset my video choice"]
```

It clears the saved YouTube choice, unloads current-page plugin videos and unchecks all their remember boxes. The button stays visible/enabled, confirms completion through an accessible status and retains keyboard focus.

For an inline control after the player loads, use:

```text
[dsgvo_video id="123" show_reset="true"]
```

Only this option reserves 64px of the configured height for a reset control. Reset does not unload other open tabs, clear Maps consent or undo information already sent to Google.

## External provider and privacy

The configured player is delayed until consent or a remembered choice. After loading, the browser contacts YouTube/Google, which can receive the IP address, device data, website origin and cookies/account-related information. The plugin retains `strict-origin-when-cross-origin` to identify the embedding website without sending its page path/query as the cross-origin referrer.

Privacy-enhanced youtube-nocookie embeds still contact Google and may show non-personalized advertising. See [YouTube's embedding guidance](https://support.google.com/youtube/answer/171780?hl=en), [Google Privacy Policy](https://policies.google.com/privacy), [YouTube Terms](https://www.youtube.com/t/terms) and [API Services Terms](https://developers.google.com/youtube/terms/api-services-terms-of-service).

The plugin does not intercept unrelated requests from other plugins, themes or embeds. Its name does not guarantee GDPR/DSGVO compliance of a whole website. Configure valid consent, appropriate notices and withdrawal options; this is independent software, not legal advice or an official YouTube product.

## Screenshots and verification

Screenshot 5 shows the optional modern-checkbox setting. Screenshots 6–7 deliberately enable it. Screenshot 8 deliberately enables `show_reset="true"`; screenshot 9 uses the separate reset shortcode. These options are not added automatically to existing videos.

Current checks are documented in [TESTING.md](TESTING.md). Old 1.0.1 screenshots/readme/catalogs were preserved separately and are not presented as new 1.1.0 evidence. The installable ZIP excludes documentation and WordPress.org marketing PNGs; the directory assets are packaged separately.

## Links and license

- Publisher/support: [Tsambasis & Tsambasis](https://tsambasis.net/)
- [Live demonstration](https://plugin-demo.m00dy.org/live-demonstration/)
- [Existing plugin-information page](https://solutionfirst.m00dy.org/wp-plugin/)

The protected demonstration/information URLs remain unchanged. `solutionfirst` remains the WordPress.org contributor account, separate from the public manufacturer. GPLv2 or later; see LICENSE and the plugin header.
