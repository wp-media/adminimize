<?php declare(strict_types = 1); # -*- coding: utf-8 -*-

namespace Adminimize\Tests\Unit\Settings\Tabs;

use Adminimize\Tests\Unit;
use Brain\Monkey\Functions;
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
    public function testNewTabs()
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
    public function testGettingTabsList()
    {
        $tabsArray = ['Test1', 'Test2'];

        Functions\expect('apply_filters')->once()->andReturn($tabsArray);

        $this->assertEquals($tabsArray, (new Tabs)->list());
    }
}
