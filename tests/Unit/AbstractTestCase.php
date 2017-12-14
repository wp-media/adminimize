<?php # -*- coding: utf-8 -*-

namespace Adminimize\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;

/**
 * Class AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase {

	/**
	 * Sets up the environment.
	 *
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Tears down the environment.
	 *
	 * @return void
	 */
	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}

}
