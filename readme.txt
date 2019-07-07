=== Adminimize ===
Contributors: Bueltge, inpsyde
Donate link: https://www.paypal.me/FrankBueltge
Tags: color, scheme, theme, admin, dashboard, color scheme, plugin, interface, ui, metabox, hide, editor, minimal, menu, customization, interface, administration, lite, light, usability, lightweight, layout, zen
Requires at least: 4.0
Tested up to: 5.2
Stable tag: 1.11.5

Adminimize that lets you hide 'unnecessary' items from the WordPress backend

== Description ==
If you manage a multi-author WordPress blog or WordPress sites for clients, then you may have wondered if it was possible to clean up the WordPress admin area for your users? There are lots of things in the WordPress admin area that your users don’t need to see or use. This plugin help you to hide unnecessary items from WordPress admin area.

Adminimize makes it easy to remove items from view based on a user’s role.

= What does this plugin do? =
The plugin changes the administration backend and gives you the power to assign rights on certain parts. Admins can activate/deactivate every part of the menu and even parts of the sub-menu. Meta fields can be administered separately for posts and pages. Certain parts of the write menu can be deactivated separately for admins or non-admins. The header of the backend is minimized and optimized to give you more space and the structure of the menu gets changed to make it more logical - this can all be done per user so each role and their resulting users can have his own settings.

= Support Custom Post Type =
The plugin support all functions also for custom post types, automatically in the settings page.

= Support Custom Options on all different post types =
It is possible to add own options to hide areas in the back-end of WordPress. It is easy and you must only forgive a ID or class, a selector, of the markup, that you will hide.

= Compatibility with plugins for MetaBoxes in Write-area =
You can add your own options, you must only check for css selectors.

**Crafted by [Inpsyde](http://inpsyde.com) · Engineering the web since 2006.**

== Installation ==
= Requirements =
* WordPress version 4.0 and later; tested only in last stable version.
* PHP 5.2.4, newer PHP versions will work faster. Tested only from version 5.6.

Use the installer via back-end of your install or ...

1. Unpack the download-package.
2. Upload the files to the `/wp-content/plugins/` directory.
3. Activate the plugin through the Plugins menu in WordPress and click Activate.
4. Administrator can go to `Settings` > `Adminimize` menu and configure the plugin (Menu, Sub-menu, Meta boxes, ...)

== Changelog ==
1.11.5 (2019-07-07)
* Fixed: Remove deprecated version for support of php 7.2 #109.
* Fixed: Dashboard removels for multible roles.
* Fixed: settings link.
* Maintenance: More solid page checks, codex issues.
* Maintenance: Remove Javascript for the settings page for scrolling top, simplify.
* Fixed: A little bid spelling is now fixed.
* Feature: Close every box other than the first, to keep the page clean.
# Fixed: Hide Meta Boxes on usage of multiple roles, Probs @filipecsweb.

1.11.4 (2017-12-14)
* Fixed hide of menu items, if you use custom menu, see [wiki page](https://github.com/bueltge/Adminimize/wiki/Custom-Menu-Order)
* Fixed Import/Export for different server environments.
* Remove languge file on github, we use always the translation community from wordpress.org
* Fixed check for settings page of Adminimize, so that we see all options, areas of the install.

1.11.3 (2017-11-16)
* Added custom dashbaord options to admin head to hide it via css.
* Added support of multiple roles for dashboard options.
* Added new option to hide 'Add New' Button on each post type.
* Fixed ID of Menu to use each link in the full width.
* Fixed error for check dashboard setup on multiple roles.
* Removed dependency from users.php to profile.php. #61
* Allow attribute selector for custom options, remove slashes in options. #65
* Change hook for change menu items ot solve order problem with third plugins. #68
* Remove Set Theme for users option - noit relevant for the plugin, old dependencies.
* Change selector to remove footer area.
* Remove Screenshots on readme page, to big, not helpful.
* Added filter hook `adminimize_nopage_access_message` to change the message for no access to a page. see the [wiki](https://github.com/bueltge/Adminimize/wiki/Filter-Hooks)

= 1.11.2 (2016-12-04) =
* Fixed backticks for `shell_exec` error.
* Fixed prevent access function for pages.

= 1.11.1 (2016-11-24) =
* Fix fatal error for WP smaller than 4.7 - Sorry again!

= 1.11.0 (2016-11-24) =
* Fix open Translations. props pedro-mendonca
* Fix Typos.
* Fix php warning on Admin Bar items for PHP 5.2.
* Fix CPT feature support, if it false.
* Add check in different functions for AJAX request.
* Add to prevent access to pages of the back end, there are active for hiding in the settings.
* Add plugin option to remove the default behavior to prevent access to pages.

= 1.10.6 (2016-08-09) =
* Fix to see Logout link also on mobile view.
* Fix type definition.

= 1.10.5 (2016-06-28) =
* Fix PHP Warning
* Fix check for active usage of Link Manager
* Fix menu var type, if is object.
* Check for multiple roles on Menu Settings, that it works only, if the option is still active on each role of this user.

= 1.10.4 (2016-06-03) =
* Add support for multiple roles to remove the Admin Bar via global options.
* Add support for multiple roles to remove the Admin Bar Back end items.
* Add also this support for Front End Admin Bar items.
* Multiple roles supported now on "Menu Options", "Global Options", "Admin Bar Back end options" and "Admin Bar Front end options".

= 1.10.3 (2016-05-11) =
* Fix exclude of set new Admin Bar on settings page of Adminimize.
* Fix check for settings page.
* Fix colors on raw, column of the settings page.
* Add buffering for debug helper in the console.
* Fix caching for Dashboard Widget options.

= 1.10.2 (2016-03-10) =
* Add possibility for custom menu slugs, especially for Plugins, Themes, there add different slug for different roles.
* Add the possibility to use the WP object cache for settings, if the webspace support this, like Memcached, APC.
* More clarity for the "own options" label.

= 1.10.1 (2016-02-29) =
* Fix the Removing of Admin Color Scheme Select on the profile page.
* Back-end options are also excluded on the settings page.
* Add new settings area for options of the plugin self.
* The support for multiple roles is now optional.
* The support for bbPress is now active and optional.

= 1.10.0 (2016-02-21) =
* Rewrite the Admin Bar settings, simplify the source and new hook to get and render the Admin Bar.
* Change settings screen for custom post type.
* Fix "select all" on Admin Bar settings.
* Fix exclude settings page for pages, there is the current screen not existent.
* Improve the exclude settings page function for hooks, there fired before `get_current_screen`.
* Remove more legacy code before WP 3.3.
* Change removal of Menu and Submenu items to WP core functions, possible to non support older WP Versions.
* Supports multiple roles on "Menu Options" and "Global Options".
* Add possibility to hide Admin Notices globally, new setting point in "Global Options".

= 1.9.2 (2016-01-30) =
* Change get role name, return now a array with slug and name to fix "Select All" function for custom roles.
* Change Menu Items to Key value, not the id. Makes possible to hide also menu items, there have a stupid menu entry.
* Remove https fix; not necessary for the plugin. If you will usage, add this custom [plugin](https://gist.github.com/bueltge/01f37a868e2e1321b931).
* Update pot and de_De language files.

= 1.9.1 (2016-25-01) =
* Bugfix for fixing ssl protocol in WP core on include styles and scripts.

= 1.9.0 (2016-01-21) =
* Change Ex-/Import functions to use JSON format and remove mysql topics, there no longer valid in WP core.
* Add more checks to hide also dynamically menu items, like Customizer.
* Update spanish and german language file.
* Fix PHP Warning [PHP Warning: in_array()](https://wordpress.org/support/topic/php-warning-in_array-expects-parameter-2-to-be-array?replies=3)
* Fix PHP Notice: Array to string conversion
* UI change: Fixed head on tables.
* Update italian language files, props to marcochiesi.
* Add global option to hide admin notices for each role.
* Replace static source to get option, only one function to get it.
* Change Admin Bar Feature: Difference between front-end and back-end.
* More stability on admin bar settings. Switch hook to set, get data of admin bar.
* Add possibilty to select/unselect all checkboxess for each area.
* Fix redirect feature, if Dashboard menu item is active for a role.
* Remove css tyles small WP 4.0
* Add minify js/css.
* Several code changes.
* Add custom fix for hide editors on post types.
* Several performance changes, like replace from `array_push`.
* Fix Role check, new function to fix [#22624](https://core.trac.wordpress.org/ticket/22624).
* Exclude Settings page and Super Admin from remove Dashboard function.

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
* Fix 'Category Height' on Meta Box on write post; See always all categories, without scrolling inside Meta Box
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
* Typo for settings message [see thread](http://wordpress.org/support/topic/plugin-adminimize-what-does-the-settings-page-ignores-this-settings-mean?replies=4)
* Fix for custom areas on Custom Post Types, [see thread](http://wordpress.org/support/topic/plugin-adminimize-bug-in-custom-metabox-ids-for-custom-types?replies=3)
* Exclude backend theme options, was used only smaller 2.0 of WP
* Exclude Hint in Footer
* Exclude write scroll options
* Different cleaner actions

= v1.7.25 =
* Update for fix menu-items with entities
* [Fix](http://plugins.trac.wordpress.org/changeset/494274) for display settings on menu, if items are deactivated
* Add Separator to settings of menu, for hide this for different roles
* Add notice for settings page, that no settings work on this page
* Fix rewrite, if change the user info area and define an rewrite
* List Separator on menu-items; also possible to hide this

= v1.7.24 =
* Maintenance: add ID for hide html-tab on Editor also in WP 3.3
* Bug fixing for WP 3.2.1 with the new functions :(

= v1.7.23 =
* Maintenance: change function to remove admin bar for WP 3.3, see [Forum item](http://wordpress.org/support/topic/694201)
* Maintenance: change for USer Info to works also in WP 3.3

= v1.7.22 =
* Security fix for $_GET on the admin-settings-page

= v1.7.21 =
* SORRY: i had an svn bug; here the complete version
* no changes; only a new commit to svn

= v1.7.20 =
* fix small bug for use plugin Localization
* add Dashboard Widgets to remove for different roles

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
* tested only in version 3.1 and 3.2-beta; don't test in smaller version
* add hindi language file

= v1.7.17 (04/11/2011) =
* Fixes on Admin-CSS Styles for WP 3.*
* Reduce backend Styles of the Plugins - Goal: kill all styles!!! (to heavy for Maintenance)

= v1.7.16 (04/01/2011) =
* Bug-fix: change init-function; admin bar also on frontend and backend and all other options of global only on backend
* Remove new hock on wp admin bar; include inline styles; only on deactivate admin bar
* Fix language errors
* Add meta box post formats
* Update de_DE language files

= v1.7.15 (03/30/2011) =
* Change functions for reduce WP Nav Menu
* change to check for super admin; add new function and option on Global Options to set this
* Maintenance: check for functions in Multisite, Super-admin for use the plugin smaller WP 3.0
* Feature: add css for more usability on settings
* Bug-fix: custom values for WP Nav Menu
* Add Option for Super Admin
* Change option for rewrite, after deactivate Dashboard; now you use a custom url, incl. http://
* Maintenance: Language File

= v1.7.14 (03/03/2011) =
* Maintenance: remove php notice on role editor
* Maintenance: Add fallback for don't load menu/sub-menu
* Maintenance: Exclude all options in different files

= v1.7.13 (03/02/2011) =
* Maintenance: different changes on code
* Maintenance: usable in WP 3.1
* Feature: Remove Admin Bar per role
* Feature: Add options for WP Nav Menu
* Bug-fix: php warning for wrong data-type [WP Forum](http://wordpress.org/support/topic/plugin-adminimize-warning-in-array)
* Bug-fix: php warning on foreach [WP Forum](http://wordpress.org/support/topic/plugin-adminimize-warning-error-invalid-argument-supplied-for-foreach)

= v1.7.12 (10/02/2010) =
* Bug-fix: Fallback for deactivate profile.php on roles smaller administration
* Bug-fix: Redirect from Dashboard on different roles
* Maintenance: small changes on code

= v1.7.11 (09/24/2010) =
* Bug-fix: for WP < 3.0; function get_post_type_object() is not exist

= v1.7.10 (09/24/2010) =
* Bug-fix: link-page in admin
* Bug-fix: meta-boxes on link-page
* Bug-fix: check for post or page with WP 3.*
* Maintenance: german language files
* Maintenance: pot-file
* Feature: new css for "User-info" in WP 3.0
* Maintenance: incl. the new css-file

= v1.7.9 (09/15/2010) =
* Bug-fix for new role-checking

= v1.7.8 (09/13/2010) =
* changes for WPMU and WP 3.0 MultiSite
* bug-fix for admin-menu in WPMU and WP 3.0 MultiSite
* bug-fix for meta boxes in WPMU and WP 3.0 MultiSite
* bug-fix for global settings in WPMU and WP 3.0 MultiSite
* bug-fix for link-options in WPMU and WP 3.0 MultiSite
* bug-fix for custom redirect after login
* different bug-fixes fpr php-warnings

= v1.7.7 (03/18/2010) =
* small fixes for redirect on deactivate Dashboard
* add dutch language file

= v1.7.6 (01/14/2010) =
* fix array-check on new option disable HTML Editor

= v1.7.5 (01/13/2010) =
* new function: disable HTML Editor on edit post/page

= v1.7.4 (01/10/2010) =
* Fix on Refresh menu and sub-menu on settings-page
* Fix for older WordPress versions and  function current_theme_supports

= v1.7.3 (01/08/2010) =
* Add Im-/Export function
* Add new meta boxes from WP 2.9 post_thumbnail, if active from the Theme
* Small modifications and code and css
* Add new functions: hide tab for help and options on edit post or edit page; category meta box with ful height, etc.

= v1.7.2 (07/08/2009) =
* Add fix to deactivate user.php/profile.php

= v1.7.1 (17/06/2009) =
* Add belorussian language file, thanks to Fat Cow

= v1.7.1 (16/06/2009) =
* changes for load user date on settings themes; better for performance on blogs with many Users
* small bug-fixes on textdomain
* changes on hint for settings on menu
* new de_DE language file
* comments meta box add to options on post

= v1.7 (23/06/2009) =
* Bug-fix for WordPress 2.6; Settings-Link
* alternate for `before_last_bar()` and change class of div

= 1.6.9 (19/06/2009) =
* Bug-fix, Settingslink gefixt;
* Changes on own defines with css selectors; first name, second css selector
* Bug-fix in own options to pages

= 1.6.8 (18/06/2009) =
* Bug-fix in german language file

= 1.6.6-7 (10/06/2009) =
* Add Meta Link in 2.8

= 1.6.5 (08/05/2009) =
* Bug-fix, Doculink only on admin page of Adminimize

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
* Bug-fix f&uuml;r rekursiven Array; Redirect bei deaktivem Dashboard funktionierte nicht

= v1.5 =
* F&uuml;r jede Nutzerrolle besteht nun die M&uuml;glichkeit, eigene Menus und Metaboxes zu setzen. Erweiterungen im Backend-Bereich und Vorbereitung f&uuml;r WordPress Version 2.7

= v1.4.7 =
* Bug-fix CSS-Adresse f&uuml;r WP 2.5

= v1.4.3-6 =
* Aufrufe diverser JS ge&auml;ndert, einige &uuml;bergreifende Funktionen nun auch ohne aktives Adminimize-Theme

= v1.4.2 =
* kleine Erweiterungen, Variablenabfragen ge&auml;ndert

= v1.4.1 =
* Bug-fixes und Umstellung Sprache

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
* Bug-fix f&uuml;r Metabox ausblenden in Write Page

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
See the [entry on the WP community forum](http://wordpress.org/support/topic/328449 "Plugin: Adminimize Help with Your own options (3 posts)") for help with this great possibility.

= License =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you can thank me and leave a [small donation](http://bueltge.de/wunschliste/ "Wishliste and Donate") for the time I've spent writing and supporting this plugin. And I really don't want to know how many hours of my life this plugin has already eaten ;)

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information about activating the translation. If you want to help to translate the plugin to your language, please have a look at the sitemap.pot file which contains all definitions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) (Windows) or use, I prefers this, the [translation service from wordpress.org](https://translate.wordpress.org/projects/wp-plugins/adminimize).

== Frequently Asked Questions ==
= Help with "Your own options" =
See the [entry on the WP community forum](http://wordpress.org/support/topic/328449 "[Plugin: Adminimize] Help with "Your own options" (3 posts)") for help with great function to add custom/own options.

= Post about the plugin with helpful hints =
 * [wpbeginner.com: How to Hide Unnecessary Items From WordPress Admin with Adminimize](http://www.wpbeginner.com/plugins/how-to-hide-unnecessary-items-from-wordpress-admin-with-adminimize/)
 * [wptavern.com: Create A Custom WordPress Admin Experience With Adminimize](http://wptavern.com/create-a-custom-wordpress-admin-experience-with-adminimize)

= I love this plugin! How can I show the developer how much I appreciate his work? =
Please send a [review](https://wordpress.org/support/view/plugin-reviews/adminimize) and let him know your care or see the [wishlist](http://bueltge.de/wunschliste/ "Wishlist") of the author. Also you can send a [donation](https://www.paypal.me/FrankBueltge).
