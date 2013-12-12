=== Cryptex | E-Mail Address Protection ===
Contributors: Andi Dittrich
Tags: email, e-mail, privacy, robots, grabbing, spam, spambots, obfuscation, protection, image, javascript, encryption, decryption, jquery, mootools, customizable, design, appearance, security, telephone, numbers, addresses
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 3.0
 
Cryptex protects E-Mail-Addresses on your website by displaying them as an image

== Description ==

The Cryptex plugin for WordPress is used to display email addresses - that are normally expressed in plain text - as an (hybrid) image automatically. Hybrid means, that the generated addresses consists of images and text simultanous - spambots/spiders using image recognition (OCR) of single generated images have no chance - they have to capture and analyze a screenshot of the whole website to grab the addresses, but this is to performance-heavy and your email addresses are protected ;) It works with telephone numbers, postal addresses also.
Just insert a shortcode like `[cryptex]youraddress@example.com[/cryptex]` to your post - that's it.

= Plugin Features =
* Fully customizable appearance: you can configure font-family, font-size and font-color - everything looks like your theme style
* Protects also E-Mail hyperlinks by using javascript based key-shifting encryption/decryption with dynamic keys - but you can use images only
* Suitable for high traffic sites - automated caching of dynamic generated images and CSS
* Native support for MooTools and jQuery is given - all other frameworks/browsers are supported by generic javascript code
* Also useable to protect postal-addresses, telephone-numbers, names and other sensitive informations
* Automatic font-search on standard system font-paths
* Supports the new modern UI style of WordPress 3.8

= UPGRADE NOTICE to 3.x =
Most of your custom **cryptex plugin settings** get lost on upgrading to the 3.x branch beacause of the new plugin backend structure - please backup them on your own.
    
== Installation ==

= System requirements =
* PHP 5.3 or greater
* GD library (v2.0.28 or greater)
* GD PNG support

= Installation =
1. Upload the complete `cryptex` folder (Wordpress Plugin) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings -> Cryptex and check all items.
4. After checking your environment (GD installed) you have to set your font-source. If the source is invalid try another one or upload fonts into the `/wp-content/plugins/cryptex/fonts/` directory and use **Plugin Directory** as font-source
5. Now - all items should be marked green, this means Cryptex is ready for use.
6. Go to the bottom of cryptex settings page and select the *font-family*, *font-color* and *font-size* like the styles in your theme
7. You can also use your own/special fonts uploading them into the `/wp-content/plugins/cryptex/fonts/` directory and use this as font-source
8. That's it! You're done. You can now enter the following code into a post or page to protect email addresses: [cryptex]youraddress@example.com[/cryptex]

== Frequently Asked Questions ==

= I get an error using the system font paths, which are shown by the settings page =

This pathes - depending on your hosting environment - can be different - if you don't know the path, please ask your hosting provider or upload the fonts manually into the cryptex-plugin-directory `\wp-content\plugins\cryptex\fonts\` and use **Plugin Directory** as font source.

= I get a "file permission" error changing the font source to **Custom Directoy** =

During security restrictions your system font paths could be unaccessable. In this case you have to upload TrueTypeFonts (.ttf) into the cryptex-plugin-directory `\wp-content\plugins\cryptex\fonts\` and use **Plugin Directory** as font source.

= I get an "file permission" php error in my blog =

The directory `/wp-content/plugins/cryptex/cache/` must be writeable - the images as well as the generated css file will be stored there

= I need bold/italic font styles =
Please use the italic/bold font of the font family you've selected. For example there is an verdana.ttf(normal) and verdanai.ttf(italic) file!

= Is it possible to use Cryptex directly in my wordpress template ? shortcode does not work yet =
Of course! you can simply use `<?php Cryptex::crypt('yourtext'); ?>` to display 'yourtext' as crypted version

= I miss some features / I found a bug =
Well..write a email to Andi Dittrich (andi DOT dittrich AT a3non DOT org) or or open a [New Issue on GitHub](https://github.com/AndiDittrich/WordPress.Cryptex/issues)

== Screenshots ==

1. Cryptex Website Appearance
2. Single vs. Hybrid Image Mode highlighted
3. Settings Page - Environment Informations + Font Settings
4. Settings Page - Crypex Appearance
5. Settings Page - Configurable Image-Offsets
6. Settings Page - CDN & Advanced Options

== Changelog ==

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
* Improoved: you can add css prefixes to fix problems with some themes without editing your theme css
* Improoved: only 1 css file is added instead of 2 files like before 
* Improoved: new plugin directory structure
* Added: I18n (internationalization) is supported
* Added: you can select the method cryptex uses to display email addresses (single image, hybrid image)
* Added: if you wanna use cryptex directly in your wordpress template, you can simply use `Cryptex::crypt('yourtext');` to display 'yourtext' as crypted version

= 1.3.5 =
* Bugfix: (websites without JQuery or MooTools) using Cryptex on more than one e-mail address per page failed: when any of the e-mail addresses getting clicked, the address from the last address gets applied to all of the links. (Thanks to **Kory S.**)

= 1.3.4 =
* Bugfix: by some misunderstanding of the WordPress API the update/upgrade/installation of **any plugins** triggered the restore/backup events of cryptex - this may be cause a "permission denied" error message during the installation of **any** plugin. *I apologize for this inconvenience*
* Improoved: dynamic CSS file is automatically generated on activating the plugin
* Improoved: suppress error messages if there is no GD-library

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
* Improoved: new plugin directory structure - moved js+css files

= 1.3.1 =
* Bugfix: restore of font folder `cryptex/fonts` failed on upgrade

= 1.3 =
* First public release.