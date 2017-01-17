## Changelog ##

### 6.0 ###
* Added: [WordPress Multisite](https://codex.wordpress.org/Create_A_Network) support 
* Added: New Environment Check to ensure Cryptex is working in a well configured environment
* Added: Support for telephone numbers including uri scheme `tel:`
* Added: Shortcode `telephone`
* Added: `href` Shortcode attribute to override the default behaviour
* Added: PLugin Upgrade Notification
* Changed: [WP-Skeleton](https://github.com/AndiDittrich/WP-Skeleton) is used as Plugin Backend Framework 
* Changed: All settings are stored in serialized form in `cryptex-options` instead of single options
* Changed: Settings Page URL to `wp-admin/options-general.php?page=Cryptex`
* Changed: Base64 Filename hashes are used instead of hexadecimal ones
* Replaced: PHP-Version-Errorpage by global admin_notice - ensure that **PHP 5.3 or greater** is used to avoid weird errors
* Bugfix: The cache accessibility check did not work on WIN platform
* Bugfix: Fixed some CSS rules used in Settings-Page
* Cleaned up the internal Plugin Structure

### 5.2 ###
* Changed: the default font-file to `LiberationSans-Regular.ttf`
* Replaced: the low-level PHP based ObjectCache by the [WordPress Transient API](https://codex.wordpress.org/Transients_API)
* Bugfix: Fatal Errors thrown in environments with missing `imageantialias()` GD function are suppressed by additional check

### 5.1 ###
* Added: Plugin Upgrade notifications fo major releases to the admins plugin page
* Added: Some unit testcases (development only)
* Bugfix: Email Address autodetection failed in some cases - thanks to [topotato on GitHub)(https://github.com/AndiDittrich/WordPress.Cryptex/issues/1)

### 5.0 ###
* Added: New Javascript decryption engine (size optimized - 827bytes)
* Added: Support for [Enlighter Syntax Highlighter](https://wordpress.org/plugins/enlighter/) to display E-Mail Addresses within highlighted content (requires Enlighter v2.7)
* Added: Invisible placeholders around the html output (required for [Enlighter](https://wordpress.org/plugins/enlighter/))
* Added: Global Javascript Object `Cryptex`
* Added: [Liberation(tm) Fonts](https://fedorahosted.org/liberation-fonts/) package
* Added: Additional HDPI Image renderer based on the [HTML5 srcset attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/img)
* Added: Settings page link to the plugin page (metadata row)
* Added: Link to author's Twitter Channel (latest Enlighter updates/news)
* Added: Option to set the CSS Font-Family (in case you want to use another font for Email addresses on your page)
* Changed HTML output attribute ordering
* Removed: MooTools + jQuery code - replaced by native version
* Removed `js-type` option - Cryptex javascript is now **always** injected into the page (`wp_footer` action)
* Removed external CSS option - the required CSS is now **always** injected into the page (`wp_head` action)
* Bugfix: HDPI CSS container is now only added when cryptex images are found on the page
* Bugfix: The contextual help link was not "full" selectable (covered by the tab nav)
* Bugfix: Error handling of the FontManager failed (should never happen under normal conditions)
* Bugfix: ObjectCache file existent check failed
* The `readme.txt` (WordPress plugin repository) is generated from the markdown file `README.md` and `CHANGES.md` (GitHub style)

### 4.0 ###
* Added: Retina/High-DPI image support
* Added: Option to set the line-height (image-height) manually
* Added [FreeType2](http://en.wikipedia.org/wiki/FreeType) support, including .otc and .otf fonts - enabled by default
* Added: Font-size can be set in px or pt - px value will be forced as default
* Added: Autodetect filter to text-widget content (optional)
* Added: Custom cache path/url settings like WordPress' media options (advancved settings)
* Added: Option to disable Antialiasing (advancved settings)
* Added: New html+image rendering engines
* Added: Width+Height attributes to generated image-tags (including server side caching)
* Added: Additional user-role check (administrator + `manage_options` required)
* Added: Tab-Panels to the settings page (Appearance, Options, Advanced)
* Added: [Contextual Help](http://codex.wordpress.org/Adding_Contextual_Help_to_Administration_Menus) based help/informations
* Added: Shortcode options to override the global cryptex-settings - feature requested on [WordPress.org Forums](https://wordpress.org/support/topic/local-font-styles-instead-of-global-choice)
* Added: Option to load stylesheet as inline content (style tag displayed in `wp_footer`)
* Added: Option to include javascript, required for hyperlink-decoding, as inline content (script tag displayed in `wp_head` or `wp_footer`)
* Added: Cleanup of generated stylesheets
* Added: New Screenshots
* Added: License Informations to settings-page footer
* Added: Error supression for system-font scanning
* Updated: MooTools Javascript code now uses `document.getElements()` instaed of leagcy `$$()` selector
* Modified: Cryptex javascript file uses UglifyJS for minification
* New settings page - now matches WordPress corporate UI style
* Removed WordPress <= 3.7 compatibility mode/legacy UI style
* Bugfix: Added some missing I18n namespaces
* Many internal changes/improvements

### 3.3 ###
* Added: Option to enable processing of "Nested-Shortcodes" within cryptex/email tags - this might be useful if your using inner shortcode which fetches some content from your database, etc. (disabled by default) 
* Bugfix: E-Mail-Address-Autodetection doesn't recognize subdomains (e.g. test.name@sub1.example.com)

### 3.2.1 ###
* Bugfix: CSS files doesn't get generated on upgrading the plugin - you have to click "save settings" to force the creation on previuos versions 

### 3.2 ###
* Added: Autodetection filters for `get_the_excerpt`, `get_comment_text` and `get_comment_excerpt`
* Added: New Security-Mode "Advanced Multipart Image" - each part of the email-address (seperated by dot's and @ sign) is displayed as an image, the dividers as plain text
* Added: New Security-Mode "Text" - not recommended but usefull if you don't want to use images (e.g. screenreaders required) - this setting will only modify the @sign of the text and adds mailto
* Added: Option to enable/disable `the_excerpt`, `the_content`, `get_comment_text` and `get_comment_excerpt` autodetect filters
* Added: Text-Replacement for the **dot (.)** within E-Mail-Addresses
* Improved: Increased the robustness of the Font-Manager (font detection)
* Improved: Font-List is now alphabetically sorted
* Bugfix: Limited the total number of directories to be recursively scanned by the Font-Manager to **100**. This will prevent plugin-crashes by e.g. scanning the whole filesystem. A minimum Font-Path length of 4 characters is also required (using root path **/** is now permitted)
* Bugfix: Misspelled variable within Cryptex Main class (no influence)

### 3.1 ###
* Added: E-Mail-Address autodetection - transforms E-Mail-Addresses from post/pages directly into images without the need of shortcodes (**optionally** - you can turn it off on the settings page)
* Added: I18n generation tools, including ANT build script
* Added: German translation (de_DE)
* Added: I18n can be disbaled
* Added: Environment Pre-Check (PHP 5.3 requirement!)
* Improved UI
* Bugfix: Added missing I18n domains
* Bugfix: Misspelled variable within image-hash generation (caused php error)

### 3.0 ###
* New plugin backend structure
* PHP Namespaces used to isolate plugin (PHP >= 5.3 required!)
* Improved settings page, new design
* Improved E-Mail-Address detection
* Many performance optimizations
* Removed support for GD library version 1.x (>= v2.0.38 required)
* Added: Support for new WordPress backend UI style
* Added: Automated Font Search/Detection
* Added: Shortcode-Alias [email] to be compatible with other plugins
* Added: User defined dimension offsets for generated images
* Added: FULL I18n support (internationalization)
* Bugfix: Cache not cleared on activating plugin (required for updates)
* Bugfix: Closed possible attack vector on image filenames by decoding the used sha1 hashes - now a unique salt is used to prevent it
* Bugfix: Invalid px/pt transformation used for GD1/2 interoperability
* Bugfix: Wrong css cursor was used for divider (@ sign) using hybrid mode

### 2.0 ###
* Complete rewritten version of the plugin. Completly cleaned, strict OOP coding style
* Bugfix: removed cryptex js+css from wordpress admin pages
* Bugfix: pointer cursor is only visible on active hyperlinks
* Improved: you can add css prefixes to fix problems with some themes without editing your theme css
* Improved: only 1 css file is added instead of 2 files like before 
* Improved: new plugin directory structure
* Added: I18n (internationalization) is supported
* Added: you can select the method cryptex uses to display email addresses (single image, hybrid image)
* Added: if you wanna use cryptex directly in your wordpress template, you can simply use `Cryptex::crypt('yourtext');` to display 'yourtext' as crypted version

### 1.3.5 ###
* Bugfix: (websites without JQuery or MooTools) using Cryptex on more than one e-mail address per page failed: when any of the e-mail addresses getting clicked, the address from the last address gets applied to all of the links. (Thanks to **Kory S.**)

### 1.3.4 ###
* Bugfix: by some misunderstanding of the WordPress API the update/upgrade/installation of **any plugins** triggered the restore/backup events of cryptex - this may be cause a "permission denied" error message during the installation of **any** plugin. *I apologize for this inconvenience*
* Improved: dynamic CSS file is automatically generated on activating the plugin
* Improved: suppress error messages if there is no GD-library

### 1.3.3 ###
* Bugfix: restore of font folder `cryptex/fonts` failed on upgrade (windows servers..)

### 1.3.2 ###
* Bugfix: restore of css files failed on upgrade
* Bugfix: CSS file only updated after second press on "save changes"
* Bugfix: color parsing error - colors like 0x0000ff not parsed correctly
* Bugfix: CSS font family failure on dynamic generated files
* Added: button to restore default font path `\wp-content\plugins\cryptex\fonts\`
* Added: jQuery [ColorPicker](http://www.eyecon.ro/colorpicker/) to settings page
* Added: support for custom text in cryptex shortcode (like telephone numbers, postal addresses)
* Improved: new plugin directory structure - moved js+css files

### 1.3.1 ###
* Bugfix: restore of font folder `cryptex/fonts` failed on upgrade

### 1.3 ###
* First public release.