<?php # -*- coding: utf-8 -*-

namespace Adminimize\Tests\Unit;

use Adminimize\Plugin;

/**
 * Class NetworkPostsTest
 *
 * @package Inpsyde\NetworkPosts\Tests\Unit
 */
class AdminimizeTest extends AbstractTestCase {

	/**
	 *
	 */
	public function test_basic() {

		$plugin = new Plugin( __DIR__ );
		static::assertInstanceOf( Plugin::class, $plugin );
	}

}