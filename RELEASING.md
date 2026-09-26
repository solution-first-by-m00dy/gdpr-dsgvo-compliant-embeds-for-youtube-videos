# Release 1.1.0

This release adds optional typography, messages, grouping, remembered consent and reset controls while preserving existing video appearance. Publishing source on GitHub does not release the WordPress.org plugin.

## Runtime and directory assets

The installable ZIP includes only the main PHP file, uninstall.php, LICENSE, readme.txt, includes/, assets/css/, assets/js/ and languages/. Main header, DSGVO_YT_VERSION, stable tag and catalog metadata must identify 1.1.0. Required minimums are WordPress 6.2 and PHP 7.4.

Copy verified runtime to SVN trunk and tags/1.1.0. Put directory PNGs into the separate top-level SVN assets directory, outside trunk/tags, and set image/png MIME types. Review changes and complete the WordPress.org release confirmation for the actual contributor account. See the official [SVN guide](https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/) and [asset guide](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/).

Screenshot subjects 1–9 have English and German (-de) versions. They must be actual current-version captures. Subjects 6/7 explicitly enable Modern design, 8 explicitly enables show_reset="true", and 9 uses [dsgvo_video_reset]. The old 1.0.1 screenshots are archived and must not be relabeled as current. The publisher banner was rebranded with built-in Imagegen and exported at 1544×500 and 772×250; existing icon PNGs are unchanged. Prompts/master are retained with delivery branding files.

## Upgrade behavior

- Post type dsgvo_video, IDs and [dsgvo_video] remain unchanged.
- Empty new font fields inherit previous theme/plugin styling. Messages, group loading and remembering start disabled; modern checkboxes and reset controls require explicit selection.
- Existing safe dimensions, iframe styling and percentage-based aspect-ratio behavior are retained. Invalid new inputs preserve previous settings; unsafe iframe sources are rejected.
- The optional cookie is dsgvo_yt_consent, lasts up to 180 days and is independent of Maps consent.
- Separate reset uses [dsgvo_video_reset]; the inline show_reset="true" option consumes 64px of the configured player height.
- No remote-video availability, ad-free YouTube playback or legal-compliance guarantee is made.

The author is Tsambasis & Tsambasis at https://tsambasis.net/. Contributors: solutionfirst remains the existing WordPress.org account; no profile/account rename is claimed. The plugin-demo.m00dy.org and solutionfirst.m00dy.org/wp-plugin/ URLs are intentionally retained.

## Packaging and checks

See TESTING.md for actual completed checks and remaining limits. Package only after current tests/screenshots are ready. The packaging script reads the header version, verifies the stable tag and current screenshot evidence, records hashes and refuses to replace an existing archive with different bytes. Keep older artifacts intact. Validate the exact ZIP on the isolated WordPress instance before release.
