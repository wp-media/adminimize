<?php # -*- coding: utf-8 -*-

putenv('TESTS_PATH='.__DIR__);
putenv('LIBRARY_PATH='.dirname(__DIR__));

$vendor = dirname(dirname(dirname(__FILE__))) . '/vendor/';

if (!realpath($vendor)) {
	die('Please install via Composer before running the tests.');
}

if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
    define('PHPUNIT_COMPOSER_INSTALL', $vendor . 'autoload.php');
}


require_once $vendor . 'autoload.php';

unset($vendor);
