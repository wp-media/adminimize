#!/bin/bash

# Exit if anything fails AND echo each command before executing.
# http://www.peterbe.com/plog/set-ex
set -ex

# start webserver.
php -S "$WORDPRESS_URL" -t "$WORDPRESS_DIR" >/dev/null 2>&1 &

# symlink the plugin in the WordPress plugin folder
ln -s $TRAVIS_BUILD_DIR $WORDPRESS_DIR/wp-content/plugins/inpsyde-google-tag-manager

# show the plugins folder contents to make sure the plugin folder is there
ls $WORDPRESS_DIR/wp-content/plugins