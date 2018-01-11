#!/bin/bash

# Exit if anything fails AND echo each command before executing.
# http://www.peterbe.com/plog/set-ex
set -ex

# Download.
mkdir -p $WORDPRESS_DIR
vendor/bin/wp core download --force --version=$WORDPRESS_VERSION --path=$WORDPRESS_DIR

# Create config.
rm -f ${WORDPRESS_DIR}wp-config.php
vendor/bin/wp core config --path=$WORDPRESS_DIR --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASS --dbhost=$DB_HOST

# Install.
vendor/bin/wp db create --path=$WORDPRESS_DIR
vendor/bin/wp core install --path=$WORDPRESS_DIR --url=$WORDPRESS_URL --title="wordpress.dev" --admin_user="admin" --admin_password="admin" --admin_email="admin@wp.dev"