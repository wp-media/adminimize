<?php

class DebugListener {

	/**
	 * Store message and data for output.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Set default message and set var.
	 *
	 * @param string $message Message about the data.
	 * @param mixed  $data    The data for debugging.
	 */
	public function listen( $message, $data ) {
		if ( ! $message ) {
			$message = 'Debug in Console via Adminimize Plugin:';
		}
		$this->data = array( $message, $data );
	}

	/**
	 * Print the message and data inside the console of the browser.
	 */
	public function dump() {
		// Buffering.
		ob_start();
		$output = '';
		foreach ( $this->data as $entry ) {
			$output .= 'console.info(' . json_encode( $entry[0] ) . ');';
			$output .= 'console.log(' . json_encode( $entry[1] ) . ');';
		}

		echo sprintf( '<script>%s</script>', $output );
	}
}
