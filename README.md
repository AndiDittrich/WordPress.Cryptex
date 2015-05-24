# Cryptex | Email Address Obfuscation #
Contributors: Andi Dittrich
Tags: email, e-mail, privacy, robots, grabbing, spam, spambots, retina, highdpi, responsive, obfuscation, protection, image, javascript, encryption, decryption, jquery, mootools, customizable, design, appearance, security, telephone, numbers, addresses, filter, automatically
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 5.0
License: MIT X11-License
License URI: http://opensource.org/licenses/MIT

Cryptex transforms plain-text E-Mail-Addresses into Images - automatically - No scrapers. No harvesters. No spambots. That's our goal!

## Description ##

The plugin is used to display email addresses - that are normally expressed in plain text - as an image automatically.
This will stop harvesters and crawlers from gathering sensitive data from your website.
It works with emails, telephone numbers, postal addresses or any other text-content.
Just wrap your E-Mail-Address into a shortcode like `[email]youraddress@example.com[/email]` - that's it.
Or use the **Autodetect** filter to transform every E-Mail-Address on your page automatically into an image!

### Plugin Features ###
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

### Text-Transformations ###
The @-sign as well as dot's within the e-mail-addresses can be automatically replaced by different placeholders, e.g. `mail(at)example{dot}org`, to match your website's corporate design.

### Security Modes ###
Cryptex provides various obfuscation modes for E-Mail-Addresses:

* Plain Text - only text-transformations are applied - no use of images
* Single Image - the e-mail-address is expressed as a single image 
* Multipart Image - the e-mail-address is splitted into two images, seperated by the @-sign in plain text
* Advanced Multipart Image - the craziest one: each part (divided by dot's and @ sign) is displayed as a seperate image, the dividers as plain text

## Installation ##

### System requirements ###
* PHP 5.3 or greater
* GD library (v2.0.28 or greater)
* GD PNG support
* FreeType2 (optional, required for OpenType fonts)
* Accessible cache directory (`/wp-content/plugins/cryptex/cache/` or a custom one)

### WordPress Theme requirements ###
* The `wp_footer` and `wp_head` action have to be used

### Installation ###
1. Upload the complete `cryptex` folder (Wordpress Plugin) to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings -> Cryptex and check all items into the sidebar.
4. In case that there are no font available on your server you can use own/special fonts by uploading them into the `/wp-content/plugins/cryptex/fonts/` directory 
5. Go to the appearance section and select the *font-family*, *font-color* and *font-size* like the styles in your theme
6. That's it! You're done. You can now enter the following code into a post or page to protect email addresses: [email]youraddress@example.com[/email]. Or use the Autodetect feature

## Frequently Asked Questions ##

### Cryptex Shortcode doesn't work in Text-Widgets ###

Generally, WordPress does not process any shortcodes used in text-widgets. You can add the following code to your template `functions.php` file to enable shortcode processing: `add_filter('widget_text', 'do_shortcode');`

### I get an error using the system font paths, which are shown by the settings page ###

This paths - depending on your hosting environment - can be different - if you don't know the path, please ask your hosting provider or upload the fonts manually into the cryptex-plugin-directory `\wp-content\plugins\cryptex\fonts\` and use **Plugin Directory** as font source.

### I get a "file permission" error changing the font source to **Custom Directoy** ###

During security restrictions your system font paths could be unaccessable. In this case you have to upload TrueTypeFonts (.ttf) into the cryptex-plugin-directory `\wp-content\plugins\cryptex\fonts\` and use **Plugin Directory** as font source.

### I get an "file permission" php error in my blog ###

The directory `/wp-content/plugins/cryptex/cache/` must be writeable - the images as well as the generated css file will be stored there. Try to set chmod to `0644` or `0770`

### I need bold/italic font styles ###
Please use the italic/bold font of the font family you've selected. For example there is an verdana.ttf(normal) and verdanai.ttf(italic) file!

### Is it possible to use Cryptex directly in my wordpress template ? shortcode does not work yet ###
Of course! you can simply use `<?php Cryptex::crypt('yourtext'); ?>` to display 'yourtext' as crypted version

### I miss some features / I found a bug ###
Send an email to Andi Dittrich (andi _D0T dittrich At a3non .dOT org) or or open a [New Issue on GitHub](https://github.com/AndiDittrich/WordPress.Cryptex/issues)

## Screenshots ##

1. Cryptex Website Appearance
2. Settings Page - Contextual Help Menu
3. Settings Page - Appearance
4. Settings Page - Autodetect Filters & CDN Options
5. Settings Page - System Informations
6. Settings Page - Image Offsets & Retina/HighDpi Options

## Upgrade Notice ##

### 5.0 ###
A new Javascript decoder is used - this will **break custom user modifications**! Please test it before upgrading

### 4.0 ###
After upgrading, go to the Cryptex settings page, check all options and click "Apply Settings" to force an update of the generated CSS files!

