=== Adminimize ===
Contributors: Bueltge
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4578111
Tags: color, scheme, theme, admin, dashboard, color scheme, plugin, interface, ui, metabox, hide, editor, minimal, menu, customization, interface, administration, lite, light, usability, lightweight, layout, zen
Requires at least: 2.5
Tested up to: 4.2
Stable tag: 1.8.5

Adminimize that lets you hide 'unnecessary' items from the WordPress backend

== Description ==
If you manage a multi-author WordPress blog or WordPress sites for clients, then you may have wondered if it was possible to clean up the WordPress admin area for your users? There are lots of things in the WordPress admin area that your users don’t need to see or use. This plugin help you to hide unnecessary items from WordPress admin area.

Adminimize makes it easy to remove items from view based on a user’s role.

= Search for helping hands =
Over the time the plugin was extended the plugin with much requirements and his solutions. But the source is not easy to maintain for me, I’m unhappy with the source. I have learned about coding, architecture etc.

Currently I search for developer there will help on dev and support the plugin. The plugin have a [github repository](https://github.com/bueltge/Adminimize) to easy add a issue or a create a fork, pull request. Also to add issues to understand problems.

Especially the functionality for WP Multisite is currently more a hack as a solution. But is very complex, not easy to create a solution for this.

For development see this repository: https://github.com/bueltge/Adminimize
The master branch is the current version and the new will leave in the v2.0 branch.

= Support Custom Options on all different post types =
With version 1.6.1 it is possible to add own options for hide areas in the backend of WordPress. It is easy and you must only forgive ID or class of the tag. Also it is possible to use a fixed menu and header.

= Support Custom Post Type =
Also it is possible with version 1.7.18 to use on custom post types; hide 'unnecessary' areas on the custom post types for different roles and post types.

= Compatibility with the plugins for MetaBoxes in Write-area =
1. You can add your own options, you must only see for css selectors

= Requirements =
1. WordPress version 2.8 and later

= What does this plugin do? =
The plugin changes the administration backend and gives you the power to assign rights on certain parts. Admins can activate/deactivate every part of the menu and even parts of the submenu. Meta fields can be administered separately for posts and pages. Certain parts of the write menu can be deactivated separately for admins or non-admins. The header of the backend is minimized and optimized to give you more space and the structure of the menu gets changed to make it more logical - this can all be done per user so each user can have his own settings.

= Details =
1. the admin theme can be set per user. To change this go to user settings
1. currently you can use the theme together with the color settings for the Fresh and Classic themes
1. more colors can be easily added
1. new menu structure: on the left hand site you find classic menu points for managing and writing, while the right part is reserved for settings, design, plugins and user settings
1. the dashboard has been moved into the menu itself but this can be deactivated if its not desired
1. the menu is now smaller and takes up less space
1. the WRITE menu has been changed as follows:
1. it is no longer limited to a fixed width but flows to fill your whole browser window now
1. you can scroll all input fields now, no need to make certain parts of the WRITE screen bigger
1. categories moved to the sidebar
1. tags moved to the sidebar if you are not using the plugin "Simple Tags"
1. the editing part gets auto-scrolled which makes sense when using a small resolution
1. the media uploader now uses the whole screen width
1. supports the plugin "Admin Drop Down Menu" - when the plugin is active the user has two more backend-themes to chose from
1. supports the plugin "Lighter Menus" - when that plugin is active the user has another two backend-themes to chose from
1. two new color schemes are now available
1. the width of the sidebar is changeable to standard, 300px, 400px or 30%
1. each meta field can now be deactivated (per user setting) so it doesn't clutter up your write screen
1. you can even deactivate other parts like h2, messages or the info in the sidebar
1. the part of the user info you have on the upper - right part of your menu can be deactivated or just the log-out link
1. the dashboard can be completely removed from the backend
1. all menu and sub menu points can be completely deactivated for admins and non-admins
1. most of these changes are only loaded when needed - i.e. only in the write screen
1. set a backend-theme for difficult user
1. you can set an role to view the areas on link page, edit post, edit page and global
1. you can add own options for set rights to role
1. it is possible to disable HTML-Editor on edit-area, only Visual-tab
1. remove widgets in widgets settings for different role
1. remove admin bar for different role
1. remove admin bar items for different role
1. remove items on custom post types for different role
1. ... many many more

== Installation ==
1. Unpack the download-package
2. Upload folder include all files to the `/wp-content/plugins/` directory. The final directory tree should look like `/wp-content/plugins/adminimize/adminimize.php`, `/wp-content/plugins/adminimize/adminimize_page.php`, `/wp-content/plugins/adminimize/css/` and `/wp-content/plugins/adminimize/languages`
3. Activate the plugin through the `Plugins` menu in WordPress
4. Selecting Colour Scheme and Theme, selection in Your Profile, go to your User Profile (under `Users` > `Your Profile` or by clicking on your name at the top right corner of the administration panel).
5. Administrator can go to `Options` > `Adminimize` menu and configure the plugin (Menu, Submenu, Metaboxes, ...)

* or use the automatic install via backend of WordPress

= Advice =
Please use the `Deinstall-Function` in the option-area before update to version 1.4! Version 1.4 and higher have only one database entry and the `Deinstall-Option` deinstall the old entry's.

See on [the official website](http://bueltge.de/wordpress-admin-theme-adminimize/674/ "Adminimize").

== Screenshots ==
1. Settings in WordPress 3.2-beta with two Custom Post Types
1. configure-area for user/admin; options for metaboxes, areas in write-area and menu in WordPress 2.7/2.8
1. configure-area for user in WordPress 2.7/2.8
1. Small tweak for design higher WP 2.7, save 50px over the menu
1. minimize header after activate in WordPress 2.5
1. configure-area for user in WordPress 2.5
1. Adminimize Theme how in WordPress 2.3

== Changelog ==
= 1.8.5 (2015-03-19) =
* Add brazilian portuguese translation, thanks to [Rafael Funchal](http://www.rafaelfunchal.com.br/)
* Small code changes for php notices
* Fix Admin Bar Feature
* Different code maintenance
* Enhance readme for helpful links under FAQ
* Fix to remove admin bar

= 1.8.4 (06/06/2013) =
* Change Widget Settings, better to unregister widgets from other themes and plugins
* Add more usability to the settings page
* Small major changes

= 1.8.3 (04/07/2013) =
* Fix for use it with bbPress
* Small minor changes

= 1.8.2 (02/15/2013) =
* Fix PHP Notice message for empty var, see [support](http://wordpress.org/support/topic/undefined-index-current_screen)
* Changes for load files and functions only, if it necessary
* Fix, that the changes on Admin Bar work always in all admin pages

= 1.8.1 (01/10/2013) =
* Fix PHP notice on message for network
* Check for active links manager; change from WP 3.5
* Add Widget settings (Beta)
* Fix for remove admin bar in backend
* Remove Backend options, there not usable with WP 3.5 and earlier
* Fix 'Category Height' on Meta Box on write post; See always all categories, without scolling inside Meta Box
* Fix to hide footer, but this is still usable by adding custom content
* Fix Hints, Options for Multisite install
* Add Admin Bar options (Beta)

= v1.8.0 =
* Simple Support for WP Multisite
* Enhancement for hide Text-Tab on editors in custom post types
* Small fix for PHP notice

= v1.7.27 =
* Fix for hide Admin Bar in WP 3.4
* Fix for remove sections on custom post types in edit screen table
* Enhancements for reduce sections on edit post and page
* Enhancement for User Info to use also in Admin Bar in front end
* Fix for different pages in admin, see [forum thread](http://wordpress.org/support/topic/plugin-adminimize-hide-page-and-subpages-editphp)
* Fix, if you don't use redirect for php notice
* Add romanian language

= v1.7.26 =
* Grammerfix for settings message [see thread](http://wordpress.org/support/topic/plugin-adminimize-what-does-the-settings-page-ignores-this-settings-mean?replies=4)
* Fix for custom areas on Custom Post Types, [see thread](http://wordpress.org/support/topic/plugin-adminimize-bug-in-custom-metabox-ids-for-custom-types?replies=3)
* Exclude backend theme options, was used only smaller 2.0 of WP
* Exclude Hint in Footer
* Exclude writescroll options
* Different cleaner actions

= v1.7.25 =
* Update for fix menu-items with entities
* [Fix](http://plugins.trac.wordpress.org/changeset/494274) for display settings on menu, if items are deactivated
* Add Separator to settings of menu, for hide this for different roles
* Add notice for settings page, that no settings work on this page
* Fix rewrite, if change the user info area and define an rewrite
* List Separator on menu-items; also possible to hide this

= v1.7.24 =
* Maintenance: add ID for hide html-tab on Edtior also in WP 3.3
* Bugfixing for WP 3.2.1 with the new functions :(

= v1.7.23 =
* Maintenance: change function to remove admin bar for WP 3.3, see [Forum item](http://wordpress.org/support/topic/694201)
* Maintenance: change for USer Info to works also in WP 3.3

= v1.7.22 =
* Security fix for $_GET on the admin-settings-page

= v1.7.21 =
* SORRY: i had an svn bug; here the cimplete version
* no changes; only a new commit to svn

= v1.7.20 =
* fix small bug for use plugin Localization
* add Dashbaord Widgets to remove for different roles

= v1.7.19 =
* fix page for links - `link.php`
* add irish language files
* add bulgarian language files

= v1.7.18 (06/07/2011) =
* Fixes Small User info on right top with Admin Bar, also ready for WP 3.2
* Fixes Error for xmlrpc
* Add QuickEdit-Areas for hide this
* Different changes on source
* With WP 3.2 remove all Admin Styles !
* Add support for custom post type
* many small changes on source
* update de_DE language files
* tested only in version 3.1 and 3.2-beta; dont test in smaller version
* add hindi language file

= v1.7.17 (04/11/2011) =
* Fixes on Admin-CSS Styles for WP 3.*
* Reduce backend Styles of the Plugins - Goal: kill all styles!!! (to heavy for Maintenance)

= v1.7.16 (04/01/2011) =
* Bugfix: chnage init-function; admin bar also on frontend and backend and all other options of global only on backend
* Remove new hoock on wp admin bar; incoe inline styles; only on deactivate admin bar
* Fix language errors
* Add meta box post formats
* Update de_DE language files

= v1.7.15 (03/30/2011) =
* Change functions for reduce WP Nav Menu
* change to check for super admin; add new function and option on Global Options to set this
* Maintenance: check for functions in Multisite, Superadmin for use the plugin smaller WP 3.0
* Feature: add css for more usability on settings
* Bugfix: custom values for WP Nav Menu
* Add Option for Super Admin
* Change option for rewrite, after deactivate Dashboard; now you use a custom url, incl. http://
* Maintenance: Language File

= v1.7.14 (03/03/2011) =
* Maintenance: remove php notice on role editor
* Maintenance: Add fallback for dont load menu/submenu
* Maintenance: Exclude all options in different files

= v1.7.13 (03/02/2011) =
* Maintenance: different changes on code
* Maintenance: usable in WP 3.1
* Feature: Remove Admin Bar per role
* Feature: Add options for WP Nav Menu
* Bugfix: php warning for wrong datatype [WP Forum](http://wordpress.org/support/topic/plugin-adminimize-warning-in-array)
* Bugfix: php warning on foreach [WP Forum](http://wordpress.org/support/topic/plugin-adminimize-warning-error-invalid-argument-supplied-for-foreach)

= v1.7.12 (10/02/2010) =
* Bugfix: Fallback for deactivate profile.php on roles smaller administration
* Bugfix: Redirect from Dashboard on different roles
* Maintenance: small changes on code

= v1.7.11 (09/24/2010) =
* Bugfix: for WP < 3.0; function get_post_type_object() is not exist

= v1.7.10 (09/24/2010) =
* Bugfix: link-page in admin
* Bugfix: meta-boxes on link-page
* Bugfix: check for post or page with WP 3.*
* Maintenance: german language files
* Maintenance: pot-file
* Feature: new css for "User-info" in WP 3.0
* Maintenance: incl. the new css-file

= v1.7.9 (09/15/2010) =
* Bugfix for new role-checking

= v1.7.8 (09/13/2010) =
* changes for WPMU and WP 3.0 MultiSite
* bugfix for admin-menu in WPMU and WP 3.0 MultiSite
* bugfix for metaboxes in WPMU and WP 3.0 MultiSite
* bugfix for global settings in WPMU and WP 3.0 MultiSite
* bugfix for link-options in WPMU and WP 3.0 MultiSite
* bugfix for custom redirect after login
* different bugfixes fpr php-warnings

= v1.7.7 (03/18/2010) =
* small fixes for redirect on deactivate Dashboard
* add dutch language file

= v1.7.6 (01/14/2010) =
* fix array-check on new option disable HTML Editor

= v1.7.5 (01/13/2010) =
* new function: disable HTML Editor on edit post/page

= v1.7.4 (01/10/2010) =
* Fix on Refresh menu and submenu on settings-page
* Fix for older WordPress verisons and  function current_theme_supports 

= v1.7.3 (01/08/2010) =
* Add Im-/Export function
* Add new meta boxes from WP 2.9 post_thumbnail, if active from the Theme
* Small modifications and code and css
* Add new functions: hide tab for help and options on edit post or edit page; category meta box with ful height, etc.

= v1.7.2 (07/08/2009) =
* Add fix for deactive user.php/profile.php

= v1.7.1 (17/06/2009) =
* Add belarussian language file, thanks to Fat Cow

= v1.7.1 (16/06/2009) =
* changes for load userdate on settings themes; better for performance on blogs with many Users
* small bugfixes on texdomain
* changes on hint for settings on menu
* new de_DE language file
* comments meta box add to options on post

= v1.7 (23/06/2009) =
* Bugfix for WordPress 2.6; Settings-Link
* alternate for `before_last_bar()` and change class of div

= 1.6.9 (19/06/2009) =
* Bugfix, Settingslink gefixt;
* Changes on own defines with css selectors; first name, second css selector
* Bugfix in own options to pages

= 1.6.8 (18/06/2009) =
* Bugfix in german language file

= 1.6.6-7 (10/06/2009) =
* Add Meta Link in 2.8

= 1.6.5 (08/05/2009) =
* Bugfix, Doculink only on admin page of Adminimize

= 1.6.4 (27/04/2009) =
* new Backend-Themes
* more options
* multilanguage for role-names

= 1.6.1, 1.6.3 (24/05/2009) =
* ready for own roles
* new options for link-area on WP backend
* own options for all areas, use css selectors
* ...

= v1.6 =
* ready for WP 2.7
* new options area, parting of page and post options
* add wp_nonce for own logout
* ...

= v1.5.3-8 =
* Changes for WP 2.7
* changes on CSS design
* ...

= v1.5.2 =
* own redirects possible

= v1.5.1 =
* Bugfix f&uuml;r rekursiven Array; Redirect bei deaktivem Dashboard funktionierte nicht

= v1.5 =
* F&uuml;r jede Nutzerrolle besteht nun die M&uuml;glichkeit, eigene Menus und Metaboxes zu setzen. Erweiterungen im Backend-Bereich und Vorbereitung f&uuml;r WordPress Version 2.7

= v1.4.7 =
* Bugfix CSS-Adresse f&uuml;r WP 2.5

= v1.4.3-6 =
* Aufrufe diverser JS ge&auml;ndert, einige &uuml;bergreifende Funktionen nun auch ohne aktives Adminimize-Theme

= v1.4.2 =
* kleine Erweiterungen, Variablenabfragen ge&auml;ndert

= v1.4.1 =
* Bugfixes und Umstellung Sprache

= v1.4 =
* Performanceoptimierung; <strong>Achtung:</strong> nur noch 1 Db-Eintrag, bei Update auf Version 1.4 zuvor die Deinstallation-Option nutzen und die Db von &uuml;berfl&uuml;ssigen Eintr&auml;gen befreien.

= v1.3 =
* Backendfunktn. erweitert, Update f&uuml;r PressThis im Bereich Schreiben, etc.

= v1.2 =
* Erweiterungen der MetaBoxen

= v1.1 =
* Schreiben-, Verwalten-Bereich ist deaktivierbar; CSS-Erweiterungen des WP 2.3 Themes f&uuml;r WP 2.6; Sidebar im Schreiben-Bereich noch mehr konfigurierbar, Optionsseite ausgebaut, kleine Code-Ver&auml;nderungen

= v1.0 =
* JavaScript schlanker durch die Hilfe von <a href="http://www.schloebe.de/">Oliver Schl&uuml;be</a>

= v0.8.1 =
* Hinweis im Footer m&uuml;glich, optional mit optionalen Text, Weiterleitung immer ersichtlich

= v0.8 =
* Weiterleitung nach Logout m&uuml;glich

= v0.7.9 =
* Zus&auml;tzlich ist innerhalb der Kategorien nur "Kategorien hinzuf&uuml;gen" deaktiverbar

= v0.7.8 =
* Mehrsprachigkeit erweitert

= v0.7.7 =
* Bugfix f&uuml;r Metabox ausblenden in Write Page

= v0.7.6 =
* Checkbox f&uuml;r alle ausw&auml;hlen auch in Page und Post, Korrektur in Texten

= v0.7.5 =
* Checkbox f&uuml;r alle ausw&auml;hlen, Theme zuweisen

= v0.7.3 =
* Optionale Weiterleitung bei deaktiviertem Dashboard, Einstellungen per Plugin-Seite m&uuml;glich, Admin-Footer erg&auml;nzt um Plugin-infos

= v0.7.2 =
* Update Options Button zus&auml;tzlich im oberen Abschnitt

= v0.7.1 =
* Thickbox Funktion optional

= v0.7 =
* WriteScroll optional, MediaButtons deaktivierbar

= v0.6.9 =
* Theme WordPress 2.3 hinzugekommen, Footer deaktivierbar


== Other Notes ==
= Help with "Your own options" =
My english ist very bad and you can see the [entry on the WP community forum](http://wordpress.org/support/topic/328449 "Plugin: Adminimize Help with Your own options (3 posts)") for help with great function.

= Licence =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a [small donation](http://bueltge.de/wunschliste/ "Wishliste and Donate") for the time I've spent writing and supporting this plugin. And I really don't want to know how many hours of my life this plugin has already eaten ;)

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information about activating the translation. If you want to help to translate the plugin to your language, please have a look at the sitemap.pot file which contains all defintions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) (Windows).

= Localizations =
* Also Thanks to [Ovidio](http://pacura.ru/ "pacaru.ru") for an translations the details in english and [G&uuml;rkan G&uuml;r](http://www.seqizz.net/ "G&uuml;rkan G&uuml;r") for translation in turkish.
* Thanks to [Gabriel Scheffer](http://www.gabrielscheffer.com.ar "Gabriel Scheffer") for the spanish language files.
* Thanks to [Andrea Piccinelli] for the italian language files.
* Thanks to Fat Cow for the belarussian language files.
* Thanks to [Rene](http://wpwebshop.com/ "wpwebshop.com") for dutch translation.
* Thanks to [GeorgWP](http://wordpress.blogos.dk/s%C3%B8g-efter-downloads/?did=208 "wordpress.blogos.dk/s%C3%B8g-efter-downloads/?did=208") for danish language files.
* Thanks to [Scavenger](http://www.photos-marseille.fr) for french language files.
* Thanks to [Outshine Solutions](http://outshinesolutions.com/web-hosting/web-hosting-india.html) for hindi language files.
* Thanks to [ray.s](http://letsbefamous.com/) for irish translation.
* Thanks for bulgarian language files to [Web Geek](http://webhostinggeeks.com/)
* Thanks for romanian language to [Alexander Ovsov - Web Geek](http://webhostinggeeks.com/)
* Thanks to [Rafael Funchal](http://www.rafaelfunchal.com.br/) for brazilian portuguese translation


== Frequently Asked Questions ==
= Help with "Your own options" =
My english ist gruesome here and there and you can see the [entry on the WP community forum](http://wordpress.org/support/topic/328449 "[Plugin: Adminimize] Help with "Your own options" (3 posts)") for help with great function.

= Post about the plugin with helpful hints =
 * [wpbeginner.com: How to Hide Unnecessary Items From WordPress Admin with Adminimize](http://www.wpbeginner.com/plugins/how-to-hide-unnecessary-items-from-wordpress-admin-with-adminimize/)
 * [wptavern.com: Create A Custom WordPress Admin Experience With Adminimize](http://wptavern.com/create-a-custom-wordpress-admin-experience-with-adminimize)

= Where can I get more information? =
Please visit [the official website](http://bueltge.de/wordpress-admin-theme-adminimize/674/ "Adminimize") for the latest information on this plugin.

= I love this plugin! How can I show the developer how much I appreciate his work? =
Please visit [the official website](http://bueltge.de/wordpress-admin-theme-adminimize/674/ "Adminimize") and let him know your care or see the [wishlist](http://bueltge.de/wunschliste/ "Wishlist") of the author.
