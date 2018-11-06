<?php

namespace Adminimize\Tests\Unit\Settings;

use Adminimize\Tests\Unit\AbstractTestCase;
use Adminimize\Settings\SettingsRepository;
use Adminimize\Exceptions\SettingNotFoundException;

class SettingsRepositoryTest extends AbstractTestCase
{
    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;

    protected function setUp()
    {
        parent::setUp();
        $this->settingsRepository = new SettingsRepository;
    }

    public function testToSeeIfOptionsKeyIsInitialized()
    {
        $this->assertEquals([], get_option(SettingsRepository::OPTION_NAME));
    }
    
    public function testSavingOptions()
    {
        $data = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];

        $this->settingsRepository->update($data);

        $this->assertEquals($data, get_option(SettingsRepository::OPTION_NAME));
    }

    public function testFetchingAllOptions()
    {
        $data = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];

        $this->settingsRepository->update($data);

        $this->assertEquals($data, $this->settingsRepository->get());
    }

    public function testFetchingSpecificOption()
    {
        $data = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];

        $this->settingsRepository->update($data);

        $this->assertEquals($data['k1'], $this->settingsRepository->get('k1'));
    }

    public function testFetchingSpecificOptionWithWrongKey()
    {
        $this->expectException(SettingNotFoundException::class);

        $data = [
            'k1' => 'v1',
            'k2' => 'v2',
            'k3' => 'v3',
        ];

        $this->settingsRepository->update($data);

        $this->settingsRepository->get('BADKEY');
    }

    protected function tearDown()
    {
        parent::tearDown();
        delete_option(SettingsRepository::OPTION_NAME);
    }
}
