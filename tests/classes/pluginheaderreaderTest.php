<?php
/**
 * Dummy FileHeaders
 */

/**
 * Plugin Name: PluginHeaderReader Tests
 * Plugin URI:  http://example.com
 * Text Domain: pluginheaderreader
 * Domain Path: /languages
 * Description: Dummy file headers
 * Author:      John Doe
 * Author URI:  http://example.com/pluginheaderreader
 * Version:     1.0
 * License:     GPLv3
 */

require_once dirname( dirname( dirname( __FILE__ ) ) ) . '\classes\pluginheaderreader.php';

/**
 * Test class for PluginHeaderReader
 */
class PluginHeaderReaderTest extends \WP_UnitTestCase
{

	public $id = 'test';

  /**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	public function setUp() {}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	public function tearDown() {}

	/**
	 * @covers PluginHeaderReader::init()
	 */
	public function testInit() {

		PluginHeaderReader::init( __FILE__, $this->id );

		$this->assertTrue( property_exists( PluginHeaderReader::$data, $this->id ) );

		// empty filename
		$this->assertFalse( PluginHeaderReader::init( 'foo', $this->id ) );

		// empty id
		$this->assertFalse( PluginHeaderReader::init( __FILE__, '' ) );

	}

	/**
	 * @covers PluginHeaderReader::get_instance()
	 */
	public function testGet_instance() {

		$instance = PluginHeaderReader::get_instance( $this->id );

		$this->assertTrue( is_object( $instance ) );

		$instance = PluginHeaderReader::get_instance( 'invalid_id' );

		$this->assertFalse( is_object( $instance ) );

	}

	/**
	 * @covers PluginHeaderReader::read()
	 */
	public function testRead() {

		PluginHeaderReader::init( __FILE__, $this->id );
		$instance = PluginHeaderReader::get_instance( $this->id );
		$id = $this->id;

		$result = $instance->read();
		$this->assertTrue( $result );

		$this->assertTrue( $instance->headers_was_set );

		$result = ( $instance->Name === 'PluginHeaderReader Tests' );
		$this->assertTrue( $result );

	}

}