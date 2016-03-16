Thanks for contributing to Adminimize &mdash; you rock!

# Adminimize
* For **general information** please refer to the [Adminimize official Plugin page](http://wordpress.org/extend/plugins/adminimize/).
* Adminimize should on minimum be backwards compatible with the two versions prior to the current stable version of WordPress.
* Adminimize respects the [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/) and follows additional [conventions and best practices](https://github.com/inpsyde/Codex/blob/master/accepted/styleguide_conventions_bestpractices_EN.md). 
* If you want to **translate Adminimize**, you best do so by means of the official [WordPress.org GlotPress](https://translate.wordpress.org/projects/wp-plugins/adminimize).

# Getting Started
* Make sure you have a [GitHub account](https://github.com/signup/free).
* See if your issue has been discussed (or even fixed) earlier. You can [search for existing issues](https://github.com/inpsyde/multilingual-press/issues?utf8=%E2%9C%93&q=is%3Aissue).
* Assuming it does not already exist, [create a new issue](https://github.com/bueltge/Adminimize/issues/new).
  * Clearly describe the issue including steps to reproduce when it is a bug.
  * Make sure you fill in the earliest version that you know has the issue.
* Fork the repository on GitHub.

# Making Changes
* Create a topic branch from where you want to base your work.
  * This is usually the `master` branch.
  * Only target release branches if you are certain your fix must be on that branch.
  * To quickly create a topic branch based on the `master` branch:
    * `git checkout -b issue/%YOUR-ISSUE-NUMBER%_%DESCRIPTIVE-TITLE% master`
    * a good example is `issue/118_html_lang_attribute`
  * Please avoid working directly on the `master` branch.
* Make commits of logical units.
* Make sure your commit messages are helpful.
* If you want to work on assets, find all relevant files in the `resources` folder. There you can initialize grunt with `npm install` and then `grunt watch` your changes.

# Submitting Changes
* Push your changes to the according topic branch in your fork of the repository.
* [Create a pull request](https://github.com/bueltge/Adminimize/compare) to our repository.
* Wait for feedback. The MLP team looks at pull requests on a regular basis.

# License
By contributing code to Adminimize, you grant its use under the [GNU General Public License v3](https://github.com/bueltge/Adminimize/blob/master/LICENSE.txt).
