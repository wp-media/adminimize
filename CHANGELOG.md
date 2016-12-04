# Change Log
All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased](https://github.com/bueltge/Adminimize/compare/1.11.0...HEAD)

## [1.11.2](https://github.com/bueltge/Adminimize/compare/1.11.2...1.11.2) - 2016-12-04
* Fix backticks for `shell_exec` error #59.
* Fix prevent access function for pages #51, #57.

## [1.11.1](https://github.com/bueltge/Adminimize/compare/1.11.0...1.11.1) - 2016-11-24
* Fix fatal error for WP smaller than 4.7 - Sorry again!

## [1.11.0](https://github.com/bueltge/Adminimize/compare/1.10.6...1.11.0) - 2016-11-24
### Fixed
* Fix open Translations. props pedro-mendonca
* Fix Typos.
* Fix php warning on Admin Bar items for PHP 5.2.
* Fix CPT feature support, if it false.

### Added
* Add check in different functions for AJAX request.
* Add to prevent access to pages of the back end, there are active for hiding in the settings.
* Add plugin option to remove the default behavior to prevent access to pages.

## [1.10.6](https://github.com/bueltge/Adminimize/compare/1.10.5...1.10.6) - 2016-08-09
### Fixed
* Fix to see Logout link also on mobile view.
* Fix type definition.

## [1.10.5](https://github.com/bueltge/Adminimize/compare/1.10.4...1.10.5) - 2016-06-28
### Fixed
* Fix PHP Warning
* Fix check for active usage of Link Manager
* Fix menu var type, if is object.
* Check for multiple roles on Menu Settings, that it works only, if the option is still active on each role of this user.

## [1.10.4](https://github.com/bueltge/Adminimize/compare/1.10.3...1.10.4) - 2016-06-03
### Added
* Add support for multiple roles to remove the Admin Bar via global options.
* Add support for multiple roles to remove the Admin Bar Back end items.
* Add also this support for Front End Admin Bar items.
* Multiple roles supported now on "Menu Options", "Global Options", "Admin Bar Back end options" and "Admin Bar Front end options".

## [1.10.3](https://github.com/bueltge/Adminimize/compare/1.10.2...1.10.3) - 2016-05-11
### Fixed
* Fix exclude of set new Admin Bar on settings page of Adminimize.
* Fix check for settings page.
* Fix colors on raw, column of the settings page.
* Fix caching for Dashboard Widget options.

### Added
* Add buffering for debug helper in the console.

## [1.10.2](https://github.com/bueltge/Adminimize/compare/1.10.1...1.10.2) - 2016-03-10
### Added
* Add possibility for custom menu slugs, especially for Plugins, Themes, there add different slug for different roles.
* Add the possibility to use the WP object cache for settings, if the webspace support this, like Memcached, APC.

### Changed
* More clarity for the "own options" label.

## [1.10.1](https://github.com/bueltge/Adminimize/compare/1.10.0...1.10.1) - 2016-02-29
### Fixed
* Fix the Removing of Admin Color Scheme Select on the profile page.

### Changed
* Back-end options are also excluded on the settings page.

### Added
* Add new settings area for options of the plugin self.
* The support for multiple roles is now optional.
* The support for bbPress is now active and optional.

## [1.10.0](https://github.com/bueltge/Adminimize/compare/1.9.2...1.10.0) - 2016-02-21
### Fixed
* Fix "select all" on Admin Bar settings.
* Fix exclude settings page for pages, there is the current screen not existent.

### Removed
* Remove more legacy code before WP 3.3.

### Changed
* Rewrite the Admin Bar settings, simplify the source and new hook to get and render the Admin Bar.
* Change settings screen for custom post type.
* Change removal of Menu and Sub-Menu items to WP core functions, possible to non support older WP Versions.
* Supports multiple roles on "Menu Options" and "Global Options".

### Added
* Add possibility to hide Admin Notices globally, new setting point in "Global Options".
* Improve the exclude settings page function for hooks, there fired before `get_current_screen`.

## 1.9.2 (2016-01-30)
* Change get role name, return now a array with slug and name to fix "Select All" function for custom roles.
* Change Menu Items to Key value, not the id. Makes possible to hide also menu items, there have a stupid menu entry.
* Remove https fix; not necessary for the plugin. If you will usage, add this custom [plugin](https://gist.github.com/bueltge/01f37a868e2e1321b931).
* Update pot and de_De language files.

## 1.9.1 (2016-25-01)
* Fixing ssl protocol in WP core on include styles and scripts.

## 1.9.0 (2016-01-21)
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
* Add possibility to select/unselect all checkboxes for each area.
* Fix redirect feature, if Dashboard menu item is active for a role.
* Remove functions for WordPress versions smaller 3.5.
* Remove css styles small WP 4.0
* Add minify js/css.
* Several code changes.
* Add custom fix for hide editors on post types.
* Several performance changes, like replace from `array_push`.
* Fix Role check, new function to fix [#22624](https://core.trac.wordpress.org/ticket/22624).
* Exclude Settings page and Super Admin from remove Dashboard function.

## 1.8.5 (2015-03-19)
* Add brazilian portuguese translation, thanks to [Rafael Funchal](http://www.rafaelfunchal.com.br/)
* Small code changes for php notices
* Fix Admin Bar Feature
* Different code maintenance
* Enhance readme for helpful links under FAQ
* Fix to remove admin bar

## 1.8.4 (06/06/2013)
* Change Widget Settings, better to unregister widgets from other themes and plugins
* Add more usability to the settings page
* Small major changes

## 1.8.3 (04/07/2013)
* Fix for use it with bbPress
* Small minor changes

## 1.8.2 (02/15/2013)
* Fix PHP Notice message for empty var, see [support](http://wordpress.org/support/topic/undefined-index-current_screen)
* Changes for load files and functions only, if it necessary
* Fix, that the changes on Admin Bar work always in all admin pages

## 1.8.1 (01/10/2013)
* Fix PHP notice on message for network
* Check for active links manager; change from WP 3.5
* Add Widget settings (Beta)
* Fix for remove admin bar in backend
* Remove Backend options, there not usable with WP 3.5 and earlier
* Fix 'Category Height' on Meta Box on write post; See always all categories, without scrolling inside Meta Box
* Fix to hide footer, but this is still usable by adding custom content
* Fix Hints, Options for Multisite install
* Add Admin Bar options (Beta)

## v1.8.0
* Simple Support for WP Multisite
* Enhancement for hide Text-Tab on editors in custom post types
* Small fix for PHP notice

## v1.7.27
* Fix for hide Admin Bar in WP 3.4
* Fix for remove sections on custom post types in edit screen table
* Enhancements for reduce sections on edit post and page
* Enhancement for User Info to use also in Admin Bar in front end
* Fix for different pages in admin, see [forum thread](http://wordpress.org/support/topic/plugin-adminimize-hide-page-and-subpages-editphp)
* Fix, if you don't use redirect for php notice
* Add romanian language

## v1.7.26
* Typo for settings message [see thread](http://wordpress.org/support/topic/plugin-adminimize-what-does-the-settings-page-ignores-this-settings-mean?replies=4)
* Fix for custom areas on Custom Post Types, [see thread](http://wordpress.org/support/topic/plugin-adminimize-bug-in-custom-metabox-ids-for-custom-types?replies=3)
* Exclude backend theme options, was used only smaller 2.0 of WP
* Exclude Hint in Footer
* Exclude write scroll options
* Different cleaner actions

## v1.7.25
* Update for fix menu-items with entities
* [Fix](http://plugins.trac.wordpress.org/changeset/494274) for display settings on menu, if items are deactivated
* Add Separator to settings of menu, for hide this for different roles
* Add notice for settings page, that no settings work on this page
* Fix rewrite, if change the user info area and define an rewrite
* List Separator on menu-items; also possible to hide this

## v1.7.24
* Maintenance: add ID for hide html-tab on Editor also in WP 3.3
* Bug fixing for WP 3.2.1 with the new functions :(

## v1.7.23
* Maintenance: change function to remove admin bar for WP 3.3, see [Forum item](http://wordpress.org/support/topic/694201)
* Maintenance: change for USer Info to works also in WP 3.3

## v1.7.22
* Security fix for $_GET on the admin-settings-page

## v1.7.21
* SORRY: i had an svn bug; here the complete version
* no changes; only a new commit to svn

## v1.7.20
* fix small bug for use plugin Localization
* add Dashboard Widgets to remove for different roles

## v1.7.19
* fix page for links - `link.php`
* add irish language files
* add bulgarian language files

## v1.7.18 (06/07/2011)
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

## v1.7.17 (04/11/2011)
* Fixes on Admin-CSS Styles for WP 3.*
* Reduce backend Styles of the Plugins - Goal: kill all styles!!! (to heavy for Maintenance)

## v1.7.16 (04/01/2011)
* Bug-fix: change init-function; admin bar also on frontend and backend and all other options of global only on backend
* Remove new hock on wp admin bar; include inline styles; only on deactivate admin bar
* Fix language errors
* Add meta box post formats
* Update de_DE language files

## v1.7.15 (03/30/2011)
* Change functions for reduce WP Nav Menu
* change to check for super admin; add new function and option on Global Options to set this
* Maintenance: check for functions in Multisite, Super-admin for use the plugin smaller WP 3.0
* Feature: add css for more usability on settings
* Bug-fix: custom values for WP Nav Menu
* Add Option for Super Admin
* Change option for rewrite, after deactivate Dashboard; now you use a custom url, incl. http://
* Maintenance: Language File

## v1.7.14 (03/03/2011)
* Maintenance: remove php notice on role editor
* Maintenance: Add fallback for don't load menu/sub-menu
* Maintenance: Exclude all options in different files

## v1.7.13 (03/02/2011)
* Maintenance: different changes on code
* Maintenance: usable in WP 3.1
* Feature: Remove Admin Bar per role
* Feature: Add options for WP Nav Menu
* Bug-fix: php warning for wrong data-type [WP Forum](http://wordpress.org/support/topic/plugin-adminimize-warning-in-array)
* Bug-fix: php warning on foreach [WP Forum](http://wordpress.org/support/topic/plugin-adminimize-warning-error-invalid-argument-supplied-for-foreach)

## v1.7.12 (10/02/2010)
* Bug-fix: Fallback for deactivate profile.php on roles smaller administration
* Bug-fix: Redirect from Dashboard on different roles
* Maintenance: small changes on code

## v1.7.11 (09/24/2010)
* Bug-fix: for WP < 3.0; function get_post_type_object() is not exist

## v1.7.10 (09/24/2010)
* Bug-fix: link-page in admin
* Bug-fix: meta-boxes on link-page
* Bug-fix: check for post or page with WP 3.*
* Maintenance: german language files
* Maintenance: pot-file
* Feature: new css for "User-info" in WP 3.0
* Maintenance: incl. the new css-file

## v1.7.9 (09/15/2010)
* Bug-fix for new role-checking

## v1.7.8 (09/13/2010)
* changes for WPMU and WP 3.0 MultiSite
* bug-fix for admin-menu in WPMU and WP 3.0 MultiSite
* bug-fix for meta boxes in WPMU and WP 3.0 MultiSite
* bug-fix for global settings in WPMU and WP 3.0 MultiSite
* bug-fix for link-options in WPMU and WP 3.0 MultiSite
* bug-fix for custom redirect after login
* different bug-fixes fpr php-warnings

## v1.7.7 (03/18/2010)
* small fixes for redirect on deactivate Dashboard
* add dutch language file

## v1.7.6 (01/14/2010)
* fix array-check on new option disable HTML Editor

## v1.7.5 (01/13/2010)
* new function: disable HTML Editor on edit post/page

## v1.7.4 (01/10/2010)
* Fix on Refresh menu and sub-menu on settings-page
* Fix for older WordPress versions and  function current_theme_supports

## v1.7.3 (01/08/2010)
* Add Im-/Export function
* Add new meta boxes from WP 2.9 post_thumbnail, if active from the Theme
* Small modifications and code and css
* Add new functions: hide tab for help and options on edit post or edit page; category meta box with ful height, etc.

## v1.7.2 (07/08/2009)
* Add fix to deactivate user.php/profile.php

## v1.7.1 (17/06/2009)
* Add belorussian language file, thanks to Fat Cow

## v1.7.1 (16/06/2009)
* changes for load user date on settings themes; better for performance on blogs with many Users
* small bug-fixes on textdomain
* changes on hint for settings on menu
* new de_DE language file
* comments meta box add to options on post

## v1.7 (23/06/2009)
* Bug-fix for WordPress 2.6; Settings-Link
* alternate for `before_last_bar()` and change class of div

## 1.6.9 (19/06/2009)
* Bug-fix, Settingslink gefixt;
* Changes on own defines with css selectors; first name, second css selector
* Bug-fix in own options to pages

## 1.6.8 (18/06/2009)
* Bug-fix in german language file

## 1.6.6-7 (10/06/2009)
* Add Meta Link in 2.8

## 1.6.5 (08/05/2009)
* Bug-fix, Doculink only on admin page of Adminimize

## 1.6.4 (27/04/2009)
* new Backend-Themes
* more options
* multilanguage for role-names

## 1.6.1, 1.6.3 (24/05/2009)
* ready for own roles
* new options for link-area on WP backend
* own options for all areas, use css selectors
* ...

## v1.6
* ready for WP 2.7
* new options area, parting of page and post options
* add wp_nonce for own logout
* ...

## v1.5.3-8
* Changes for WP 2.7
* changes on CSS design
* ...

## v1.5.2
* own redirects possible

## v1.5.1
* Bug-fix f&uuml;r rekursiven Array; Redirect bei deaktivem Dashboard funktionierte nicht

## v1.5
* F&uuml;r jede Nutzerrolle besteht nun die M&uuml;glichkeit, eigene Menus und Metaboxes zu setzen. Erweiterungen im Backend-Bereich und Vorbereitung f&uuml;r WordPress Version 2.7

## v1.4.7
* Bug-fix CSS-Adresse f&uuml;r WP 2.5

## v1.4.3-6
* Aufrufe diverser JS ge&auml;ndert, einige &uuml;bergreifende Funktionen nun auch ohne aktives Adminimize-Theme

## v1.4.2
* kleine Erweiterungen, Variablenabfragen ge&auml;ndert

## v1.4.1
* Bug-fixes und Umstellung Sprache

## v1.4
* Performanceoptimierung; <strong>Achtung:</strong> nur noch 1 Db-Eintrag, bei Update auf Version 1.4 zuvor die Deinstallation-Option nutzen und die Db von &uuml;berfl&uuml;ssigen Eintr&auml;gen befreien.

## v1.3
* Backendfunktn. erweitert, Update f&uuml;r PressThis im Bereich Schreiben, etc.

## v1.2
* Erweiterungen der MetaBoxen

## v1.1
* Schreiben-, Verwalten-Bereich ist deaktivierbar; CSS-Erweiterungen des WP 2.3 Themes f&uuml;r WP 2.6; Sidebar im Schreiben-Bereich noch mehr konfigurierbar, Optionsseite ausgebaut, kleine Code-Ver&auml;nderungen

## v1.0
* JavaScript schlanker durch die Hilfe von <a href="http://www.schloebe.de/">Oliver Schl&uuml;be</a>

## v0.8.1
* Hinweis im Footer m&uuml;glich, optional mit optionalen Text, Weiterleitung immer ersichtlich

## v0.8
* Weiterleitung nach Logout m&uuml;glich

## v0.7.9
* Zus&auml;tzlich ist innerhalb der Kategorien nur "Kategorien hinzuf&uuml;gen" deaktiverbar

## v0.7.8
* Mehrsprachigkeit erweitert

## v0.7.7
* Bug-fix f&uuml;r Metabox ausblenden in Write Page

## v0.7.6
* Checkbox f&uuml;r alle ausw&auml;hlen auch in Page und Post, Korrektur in Texten

## v0.7.5
* Checkbox f&uuml;r alle ausw&auml;hlen, Theme zuweisen

## v0.7.3
* Optionale Weiterleitung bei deaktiviertem Dashboard, Einstellungen per Plugin-Seite m&uuml;glich, Admin-Footer erg&auml;nzt um Plugin-infos

## v0.7.2
* Update Options Button zus&auml;tzlich im oberen Abschnitt

## v0.7.1
* Thickbox Funktion optional

## v0.7
* WriteScroll optional, MediaButtons deaktivierbar

## v0.6.9
* Theme WordPress 2.3 hinzugekommen, Footer deaktivierbar
