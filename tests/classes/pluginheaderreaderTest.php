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
	 * @covers PluginHeaderReader::__construct()
	 */
	public function testInit() {

		new PluginHeaderReader( $this->id, __FILE__ );

		$this->assertTrue( property_exists( PluginHeaderReader::$data, $this->id ) );

		// file not exists
		$id         = $this->id . '_2';
		$instance   = new PluginHeaderReader( $id, 'foo' );
		$reflection = new ReflectionClass( $instance );
		$data       = $reflection->getStaticPropertyValue( 'data' );

		$this->assertFalse( is_object( $data->$id ) );

		// empty id
		$this->setExpectedException( 'PHPUnit_Framework_Error_Warning' );
		$id         = '';
		$instance   = new PluginHeaderReader( $id, __FILE__ );

	}

	/**
	 * @covers PluginHeaderReader::__construct()
	 */
	public function testGet_instance() {

		$instance = new PluginHeaderReader( $this->id );

		$this->assertTrue( is_object( $instance ) );

		// invalid id
		$id         = 'invalid_id';
		$instance   = new PluginHeaderReader( $id );
		$reflection = new ReflectionClass( $instance );
		$data       = $reflection->getStaticPropertyValue( 'data' );

		$this->assertFalse( is_object( $data->$id ) );

	}

	/**
	 * @covers PluginHeaderReader::read()
	 */
	public function testRead() {

		$instance = new PluginHeaderReader( $this->id, __FILE__ );
		$id = $this->id;

		$result = $instance->read();
		$this->assertTrue( $result );

		$this->assertTrue( $instance->headers_was_set );

		$result = ( $instance->Name === 'PluginHeaderReader Tests' );
		$this->assertTrue( $result );

	}

}