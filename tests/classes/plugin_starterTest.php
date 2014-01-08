<?php
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '\classes\plugin_starter.php';

/**
 * Test class for Plugin_Starter
 */
class Plugin_starterTest extends \WP_UnitTestCase
{

	public $id = 'test';
	public $basefile = '';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	public function setUp() {

		$this->basefile = dirname( dirname( dirname( __FILE__ ) ) ) . '\adminimize.php';

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	public function tearDown() {}

	/**
	 * TextDomains are untestable with WordPressTests
	 *
	 * @covers Plugin_Starter::load_textdomain()
	 */
	public function testLoad_textdomain() {

		$plugindata    = new PluginHeaderReader( $this->id, $this->basefile );
		$pluginstarter = new Plugin_Starter();
 		$pluginstarter->basename = $this->basefile;

 		$result = $pluginstarter->load_textdomain( $plugindata );

// 		$this->assertTrue( $pluginstarter->textdomain_loaded );
		$this->assertTrue( $result );

	}

	/**
	 * @covers Plugin_Starter::load_styles()
	 */
	public function testLoad_styles() {

		$pluginstarter = new Plugin_Starter();
 		$pluginstarter->basename = $this->basefile;

 		$styles = array(
 				'style'   => array( 'file' => '/css/style.css', 'enqueue' => true ),
 				'nostyle' => array( 'file' => '/css/nostyle.css', 'enqueue' => false ),
 		);

 		$result = $pluginstarter->load_styles( $styles );

		$this->assertTrue( $result );
		$this->assertTrue( $pluginstarter->all_styles_loaded );
		$this->assertArrayHasKey( 'style', $pluginstarter->style_loaded );

	}

	/**
	 * @covers Plugin_Starter::load_scripts()
	 */
	public function testLoad_scripts() {

		$pluginstarter = new Plugin_Starter();
 		$pluginstarter->basename = $this->basefile;

 		$args = array(
 				'deps'      => array(),
 				'version'   => false,
 				'in_footer' => true
 		);

 		$scripts = array(
 				'backend'  => array( 'file' => '/js/adminimize-backend.js',  'enqueue' => true, 'args' => $args ),
 				'frontend' => array( 'file' => '/js/adminimize-frontend.js', 'enqueue' => false, 'args' => $args ),
 		);

 		$result = $pluginstarter->load_scripts( $scripts );

		$this->assertTrue( $result );
		$this->assertTrue( $pluginstarter->all_scripts_loaded );
		$this->assertArrayHasKey( 'backend', $pluginstarter->script_loaded );

		$scripts_in_footer = $GLOBALS['wp_scripts']->in_footer;

	}

}