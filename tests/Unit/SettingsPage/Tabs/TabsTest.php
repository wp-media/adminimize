<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Tests\Unit\SettingsPage\Tabs;

use Adminimize\SettingsPage\Tabs;
use Adminimize\Tests\Unit;
use Brain\Monkey\Functions;

/**
 * Unit Tests for class Adminimize\SettingsPage\Tabs\Tabs
 */
class TabsTest extends Unit\AbstractTestCase {
    /**
     * Test if class is instantiated corretly.
     *
     * @return void
     */
    public function test_new_tabs() {
        $tabs = new Tabs\Tabs();

        $this->assertNotNull( $tabs );
        $this->assertInstanceOf( Tabs\Tabs::class, $tabs );
    }

    /**
     * Test if get_tabs_list() works as expected.
     *
     * @return void
     */
    public function test_get_tabs_list() {
        $tabs_array = array(
            'Test1',
            'Test2'
        );

        Functions\expect( 'apply_filters' )
            ->once()
            ->andReturn( $tabs_array );

        $tabs = new Tabs\Tabs();

        $this->assertEquals( $tabs->get_tabs_list(), $tabs_array );
    }
}