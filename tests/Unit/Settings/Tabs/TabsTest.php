<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Tests\Unit\Settings\Tabs;

use Adminimize\Tests\Unit;
use Adminimize\Settings\View\Tabs\Tabs;

/**
 * Unit Tests for class Adminimize\SettingsPage\Tabs\Tabs
 */
class TabsTest extends Unit\AbstractTestCase
{
    /**
     * Test if class is instantiated corretly.
     *
     * @return void
     */
    public function test_new_tabs()
    {
        $tabs = new Tabs();

        $this->assertNotNull($tabs);
        $this->assertInstanceOf(Tabs::class, $tabs);
    }

    /**
     * Test if get_tabs_list() works as expected.
     *
     * @return void
     */
    public function test_get_tabs_list()
    {
        $tabsArray = [
            'Test1',
            'Test2',
        ];

        $mock = $this->getMockBuilder(Tabs::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->tabs = $tabsArray;
        $mock->method('list')->willReturn($mock->tabs);

        $this->assertEquals($tabsArray, $mock->list());
    }
}
