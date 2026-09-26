# Validation of YouTube plugin 1.1.0

Verified on 26 September 2026 with WordPress 7.1.2, PHP 8.5.7, SQLite and system Chrome through Playwright. Fixtures use a separate local YouTube installation. No Maps QA database or customer site is used.

Evidence paths beginning with youtube-dev/ refer to the local release workspace. Copies of the result reports are retained in the delivery workspace under test-results/. These development reports are not included in the published plugin folder or installable ZIP.

## Completed current-version checks

| Check | Result | Evidence |
| --- | --- | --- |
| Isolated PHP validation/security regressions | 137 passed | youtube-dev/php-regressions-results.json |
| Actual WordPress integration | 189 passed | youtube-dev/wp-integration-results.json |
| Isolated browser consent/security scenarios | 17 passed | youtube-dev/js-regression-results.json |
| Actual WordPress EN/DE UI and consent browser checks | 17 passed | youtube-dev/browser-results.json |
| Real remember-color picker and dark-editor save regressions | 7 passed | youtube-dev/remember-color-regression-results.json |
| Actual 1.0.1 versus 1.1.0 layout comparison | 608 assertions, 104 measurements, 13 cases passed | youtube-dev/legacy-dimensions-results.json |
| Isolated legacy zero-value comparisons | 9 passed | youtube-dev/legacy-zero-results.json |
| Real YouTube provider rendering and inline reset | 2 language runs passed | youtube-dev/youtube-live-smoke-en_US-results.json and de_DE equivalent |
| Official Plugin Check 2.1.0, including low-severity errors/warnings | No errors or warnings | youtube-dev/plugin-check-results.txt |
| Runtime syntax and diff whitespace | 4 PHP files, 2 JavaScript files and git diff check passed | Root release verification |
| Installation and browser smoke of the exact release ZIP | 3 passed; no JS errors; fixtures restored | youtube-dev/installed-zip-results.json |
| German bundled translations | 74 complete messages; actual German editor checked | languages/ and browser/integration reports |

The compatibility comparison covered desktop and 320px layouts, percentage heights, theme constraints, custom colors, safe legacy iframe styles and stored zero values. Six before/after PNG pairs are byte-identical. Original post IDs and metadata remained unchanged. Public SVN 1.0.1 runtime matched the Git baseline in all 11 files.

Validation covers permissions/nonces, malformed inputs, rejected iframe/style values, retention of previous values, unpublished/private content, inherited empty font fields, modern checkbox accessibility, grouped loading, 180-day remembered choices, independent Maps and YouTube cookies, and both reset controls. The separate reset remains enabled without a player, retains keyboard focus and announces completion.

## Screenshots and real provider checks

All 18 EN/DE screenshots, subjects 1 through 9, were freshly captured in WordPress and visually inspected. Modern checkbox design is explicitly selected in subject 5 and enabled in subjects 6/7. Subject 8 explicitly enables the inline reset; subject 9 uses the separate shortcode. Baseline 1.0.1 screenshots/readme/catalogs are archived separately and are not current evidence.

The ordinary integration/browser suites intercept external requests; they verify consent and iframe construction independently of YouTube availability. The two separately authorized live-provider runs used real YouTube responses: zero provider requests and zero frames before consent, then HTTP 200 and a visible player with a play button, followed by removal of all frames and restored focus after reset. No provider request or JavaScript errors occurred in the successful live runs. Video playback was not started or verified. The initial sandbox network denial was an environment restriction; after authorized network access, the test selector was updated for the observed current mobile YouTube player UI.

Temporary screenshot fixtures were removed and locale changes restored. The capture report records stateRestored and liveCaptureStateRestored as true. Main PHP, uninstall.php, LICENSE and CSS were mechanically normalized to LF without changing their logic.

## Final package verification and scope

The final PHP/WordPress and picker regressions passed after the remember-color correction. The official Plugin Check completed without errors or warnings. The exact release ZIP was installed and activated with WP-CLI --force --activate on the isolated QA instance. Three subsequent browser smoke checks passed: version/manufacturer and retained information link; saving typography in the real editor and verifying the frontend; restoring the original typography. No JavaScript errors occurred, and temporary fixtures were removed (stateRestored:true).

The plugin ZIP contains 12 runtime files (38,811 bytes); the separate directory-assets ZIP contains 22 PNGs (2,833,236 bytes). CRC verification passed, and every packaged file is byte-identical to its final source. Package identity, file counts and SHA256 hashes are recorded in package-manifest-1.1.0.json, package-identity-results.json and SHA256.txt in the delivery directory.

Automated checks do not guarantee legal compliance or provider availability, autoplay, unrestricted playback or absence of YouTube advertising. These local results do not imply publication or WordPress.org approval.
