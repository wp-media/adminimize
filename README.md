# Adminimize

This is the refactor branch, don't use it. But you can help on development.

We would create a new solution, important is the maintainance of the source code. So it is necessary that we remove, prevent redundant source code. Also we should add unittest for a lot of functions. The follow list helps to improve the plugin, other topics should we move, primarily the idea, from the current version inside the [master branch](https://github.com/bueltge/Adminimize/tree/master).

- [ ] We switch currently to this Code Style - [Inpsyde PHP Coding Standards](https://github.com/inpsyde/php-coding-standards/tree/psr) We should implenent this via Composer. Currently use the branch the version before of master branch.

- [ ] Usage of https://github.com/Chrico/wp-fields for creating fields and his validation

  - Example: https://github.com/inpsyde/inpsyde-google-tag-manager/blob/master/src/Settings/View/TabbedSettingsPageView.php#L61
  - Create Fields Example: https://github.com/inpsyde/inpsyde-google-tag-manager/blob/master/src/DataLayer/User/UserDataCollector.php#L133-L149

- [ ] Usage of [Inpsyde php Codex](https://github.com/inpsyde/php-coding-standards)
- [ ] Usage of [Robot](https://robo.li/) as task runner of development (optional, also Gulp is an option)
- [ ] Usage of https://github.com/Brain-WP/Nonces for OOP deal with WordPress Nonces
- [ ] Add Unit tests via PHP Unit
- [ ] Add Acceptance tests via Codeception 
- [x] ~The settings page should use for each settings area - see [#17](https://github.com/bueltge/Adminimize/issues/17)~ Done with #81
- [ ] Multisite usage - how we can realized this requirement; each option in each site is different?

## First Steps
We should implement in the follow order. Is not a must, however is it helpful to start to help me. The source of the current plugin can you find in the [master branch](https://github.com/bueltge/adminimize/tree/master), not fine, more bad, but all features are implement and we should refactor, rewrite this with the same goal.

- [ ] Improve CI, Travis to catch bugs, enforce style, and increase confidence in your code before I merge.
- [ ] Settings are stored in `mw_adminimize` option
- [ ] Use `WP_Cache` for support with caching environment
- [ ] Create an idea for usage in a Multisite environment
- [x] ~Settings page, include Tabs for each settings area. The settings are dynamically, really complex and lot of options for each area and each role. So that we should create a solid settings page, that we enhance via hook, function and each area should use a tab.~ Done with #81
- [x] Each settings area should use a custom class, code so that we can maintain this sepparatly.
- [ ] Implement settings and his execution for
  - [ ] Adminimize Plugin Settings
  - [ ] Admin Menu
  - [ ] Admin Sub Menu
  - [ ] Widgets for Front End
  - [ ] Widgets for Dashboard
  - [ ] Admin Bar Back End
  - [ ] Admin Bar Front End
  - [ ] Meta Box and additional areas for each Post Type
  - [ ] Custom Options for each Settings area
  - [ ] ...
