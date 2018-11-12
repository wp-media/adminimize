<?php declare(strict_types = 1); // -*- coding: utf-8 -*-

namespace Adminimize\Tests\Unit\Settings;

use Brain\Monkey\Functions;
use Adminimize\Tests\Unit\AbstractTestCase;
use Adminimize\Settings\SettingsRepository;
use Adminimize\Exceptions\SettingNotFoundException;

class SettingsRepositoryTest extends AbstractTestCase
{
    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;

    /**
     * @var array
     */
    protected $actualData = [];

    /**
     * @var array
     */
    protected $data = [
        'k1' => 'v1',
        'k2' => 'v2',
        'k3' => 'v3',
    ];

    /**
     * Setup everything.
     */
    protected function setUp()
    {
        parent::setUp();

        Functions\stubs([
            'add_option' => '__return_null',
            'update_option' => true,
            'wp_cache_get' => false,
            'wp_cache_set' => true,
        ]);

        Functions\when('delete_option')->alias(function ($key) {
            unset($this->actualData[$key]);
            return true;
        });

        Functions\when('get_option')->alias(function ($key) {
            $this->actualData[$key] = $this->data;
            return $this->actualData[$key];
        });

        delete_option(SettingsRepository::OPTION_NAME);
        $this->settingsRepository = new SettingsRepository;
    }

    public function testSavingOptions()
    {
        $this->settingsRepository->update($this->data);

        $this->assertEquals($this->data, get_option(SettingsRepository::OPTION_NAME));
    }

    public function testFetchingAllOptions()
    {
        $this->settingsRepository->update($this->data);

        $this->assertEquals($this->data, $this->settingsRepository->get());
    }

    public function testFetchingSpecificOption()
    {
        $this->settingsRepository->update($this->data);

        $this->assertEquals($this->data['k1'], $this->settingsRepository->get('k1'));
    }

    public function testFetchingSpecificOptionWithWrongKey()
    {
        $this->expectException(SettingNotFoundException::class);

        $this->settingsRepository->update($this->data);

        $this->settingsRepository->get('BADKEY');
    }
}
