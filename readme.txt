=== Cryptex | Email Address Obfuscation ===
Contributors: Andi Dittrich
Tags: email, e-mail, privacy, robots, grabbing, spam, spambots, retina, highdpi, responsive, obfuscation, protection, image, javascript, encryption, decryption, jquery, mootools, customizable, design, appearance, security, telephone, numbers, addresses, filter, automatically
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 5.0
License: MIT X11-License
License URI: http://opensource.org/licenses/MIT

Cryptex transforms plain-text E-Mail-Addresses into Images - automatically - No scrapers. No harvesters. No spambots. That's our goal!

== Description ==

The plugin is used to display email addresses - that are normally expressed in plain text - as an image automatically.
This will stop harvesters and crawlers from gathering sensitive data from your website.
It works with emails, telephone numbers, postal addresses or any other text-content.
Just wrap your E-Mail-Address into a shortcode like `[email]youraddress@example.com[/email]` - that's it.
Or use the **Autodetect** filter to transform every E-Mail-Address on your page automatically into an image!

= Plugin Features =
* Fully customizable appearance: you can configure font-family, font-size and font-color - everything looks like your theme style
* Retina/HD/High-Dpi Images - best appearance on all devices (2x, 3x or 4x resolution enhancement)
* Shortcode and/or Autodetection usage!
* Build-In E-Mail-Address-Autodetection - all addresses on your page are protected automatically (if you want it - you can also just use shortcodes!)
* Autodetection filters configurable for **the_content**, **the_excerpt**, **comments**, **comments_excerpt**, **text-widget**
* Reversible Address-Autodetection Process - your content is modified as long as the plugin is activated
* Postal-addresses, telephone-numbers, names and other sensitive information`s can be protected too
* Protects E-Mail hyperlinks (mailto) by using javascript based key-shifting encryption/decryption with dynamic keys - but you can use images only
* Suitable for high traffic sites - automated caching of dynamic generated images and CSS
* Automatic font-search (standard system font-paths)
* Supports the new modern UI style of WordPress 3.8
* Native support for [Enlighter Syntax Highlighter](https://wordpress.org/plugins/enlighter/) to display E-Mail Addresses within highlighted content (requires Enlighter v2.7)
* Includes the [Liberation(tm) Fonts](https://fedorahosted.org/liberation-fonts/) package
* TrueType as well as OpenType Fonts are supported

= Text-Transformations =
The @-sign as well as dot's within the e-mail-addresses can be automatically replaced by different placeholders, e.g. `mail(at)example{dot}org`, to match your website's corporate design.

= Security Modes =
Cryptex provides various obfuscation modes for E-Mail-Addresses:

* Plain Text - only text-transformations are applied - no use of images
* Single Image - the e-mail-address is expressed as a single image 
* Multipart Image - the e-mail-address is splitted into two images, seperated by the @-sign in plain text
* Advanced Multipart Image - the craziest one: each part (divided by dot's and @ sign) is displayed as a seperate image, the dividers as plain text

== Installation ==

= System requirements =
* PHP 5.3 or greater
* GD library (v2.0.28 or greater)
* GD PNG support
* FreeType2 (optional, required for OpenType fonts)
* Accessible cache directory (`/wp-content/plugins/cryptex/cache/` or a custom one)

= WordPress Theme requirements =
* The `wp_footer` and `wp_head` action have to be used

= Installation =
1. Upload the complete `cryptex` folder (Wordpress Plugin) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings -> Cryptex and check all items into the sidebar.
4. In case that there are no font available on your server you can use own/special fonts by uploading them into the `/wp-content/plugins/cryptex/fonts/` directory 
5. Go to the appearance section and select the *font-family*, *font-color* and *font-size* like the styles in your theme
6. That's it! You're done. You can now enter the following code into a post or page to protect email addresses: [email]youraddress@example.com[/email]. Or use the Autodetect feature

== Frequently Asked Questions ==

= Cryptex Shortcode doesn't work in Text-Widgets =

Generally, WordPress does not process any shortcodes used in text-widgets. You can add the following code to your template `functions.php` file to enable shortcode processing: `add_filter('widget_text', 'do_shortcode');`

= I get an error using the system font paths, which are shown by the settings page =

This paths - depending on your hosting environment - can be different - if you don't know the path, please ask your hosting provider or upload the fonts manually into the cryptex-plugin-directory `\wp-content\plugins\cryptex\fonts\` and use **Plugin Directory** as font source.

= I get a "file permission" error changing the font source to **Custom Directoy** =

During security restrictions your system font paths could be unaccessable. In this case you have to upload TrueTypeFonts (.ttf) into the cryptex-plugin-directory `\wp-content\plugins\cryptex\fonts\` and use **Plugin Directory** as font source.

= I get an "file permission" php error in my blog =

The directory `/wp-content/plugins/cryptex/cache/` must be writeable - the images as well as the generated css file will be stored there. Try to set chmod to `0644` or `0770`

= I need bold/italic font styles =
Please use the italic/bold font of the font family you've selected. For example there is an verdana.ttf(normal) and verdanai.ttf(italic) file!

= Is it possible to use Cryptex directly in my wordpress template ? shortcode does not work yet =
Of course! you can simply use `<?php Cryptex::crypt('yourtext'); ?>` to display 'yourtext' as crypted version

= I miss some features / I found a bug =
Send an email to Andi Dittrich (andi _D0T dittrich At a3non .dOT org) or or open a [New Issue on GitHub](https://github.com/AndiDittrich/WordPress.Cryptex/issues)

== Screenshots ==

1. Cryptex Website Appearance
2. Settings Page - Contextual Help Menu
3. Settings Page - Appearance
4. Settings Page - Autodetect Filters & CDN Options
5. Settings Page - System Informations
6. Settings Page - Image Offsets & Retina/HighDpi Options

== Upgrade Notice ==

= 5.0 =
A new Javascript decoder is used - this will **break custom user modifications**! Please test it before upgrading

= 4.0 =
After upgrading, go to the Cryptex settings page, check all options and click "Apply Settings" to force an update of the generated CSS files!

== Changelog ==

= 5.0 =
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

= 4.0 =
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

= 3.3 =
* Added: Option to enable processing of "Nested-Shortcodes" within cryptex/email tags - this might be useful if your using inner shortcode which fetches some content from your database, etc. (disabled by default) 
* Bugfix: E-Mail-Address-Autodetection doesn't recognize subdomains (e.g. test.name@sub1.example.com)

= 3.2.1 =
* Bugfix: CSS files doesn't get generated on upgrading the plugin - you have to click "save settings" to force the creation on previuos versions 

= 3.2 =
* Added: Autodetection filters for `get_the_excerpt`, `get_comment_text` and `get_comment_excerpt`
* Added: New Security-Mode "Advanced Multipart Image" - each part of the email-address (seperated by dot's and @ sign) is displayed as an image, the dividers as plain text
* Added: New Security-Mode "Text" - not recommended but usefull if you don't want to use images (e.g. screenreaders required) - this setting will only modify the @sign of the text and adds mailto
* Added: Option to enable/disable `the_excerpt`, `the_content`, `get_comment_text` and `get_comment_excerpt` autodetect filters
* Added: Text-Replacement for the **dot (.)** within E-Mail-Addresses
* Improved: Increased the robustness of the Font-Manager (font detection)
* Improved: Font-List is now alphabetically sorted
* Bugfix: Limited the total number of directories to be recursively scanned by the Font-Manager to **100**. This will prevent plugin-crashes by e.g. scanning the whole filesystem. A minimum Font-Path length of 4 characters is also required (using root path **/** is now permitted)
* Bugfix: Misspelled variable within Cryptex Main class (no influence)

= 3.1 =
* Added: E-Mail-Address autodetection - transforms E-Mail-Addresses from post/pages directly into images without the need of shortcodes (**optionally** - you can turn it off on the settings page)
* Added: I18n generation tools, including ANT build script
* Added: German translation (de_DE)
* Added: I18n can be disbaled
* Added: Environment Pre-Check (PHP 5.3 requirement!)
* Improved UI
* Bugfix: Added missing I18n domains
* Bugfix: Misspelled variable within image-hash generation (caused php error)

= 3.0 =
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

= 2.0 =
* Complete rewritten version of the plugin. Completly cleaned, strict OOP coding style
* Bugfix: removed cryptex js+css from wordpress admin pages
* Bugfix: pointer cursor is only visible on active hyperlinks
* Improved: you can add css prefixes to fix problems with some themes without editing your theme css
* Improved: only 1 css file is added instead of 2 files like before 
* Improved: new plugin directory structure
* Added: I18n (internationalization) is supported
* Added: you can select the method cryptex uses to display email addresses (single image, hybrid image)
* Added: if you wanna use cryptex directly in your wordpress template, you can simply use `Cryptex::crypt('yourtext');` to display 'yourtext' as crypted version

= 1.3.5 =
* Bugfix: (websites without JQuery or MooTools) using Cryptex on more than one e-mail address per page failed: when any of the e-mail addresses getting clicked, the address from the last address gets applied to all of the links. (Thanks to **Kory S.**)

= 1.3.4 =
* Bugfix: by some misunderstanding of the WordPress API the update/upgrade/installation of **any plugins** triggered the restore/backup events of cryptex - this may be cause a "permission denied" error message during the installation of **any** plugin. *I apologize for this inconvenience*
* Improved: dynamic CSS file is automatically generated on activating the plugin
* Improved: suppress error messages if there is no GD-library

= 1.3.3 =
* Bugfix: restore of font folder `cryptex/fonts` failed on upgrade (windows servers..)

= 1.3.2 =
* Bugfix: restore of css files failed on upgrade
* Bugfix: CSS file only updated after second press on "save changes"
* Bugfix: color parsing error - colors like 0x0000ff not parsed correctly
* Bugfix: CSS font family failure on dynamic generated files
* Added: button to restore default font path `\wp-content\plugins\cryptex\fonts\`
* Added: jQuery [ColorPicker](http://www.eyecon.ro/colorpicker/) to settings page
* Added: support for custom text in cryptex shortcode (like telephone numbers, postal addresses)
* Improved: new plugin directory structure - moved js+css files

= 1.3.1 =
* Bugfix: restore of font folder `cryptex/fonts` failed on upgrade

= 1.3 =
* First public release.