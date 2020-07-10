<?php

class DebugListener {

	protected $data = [];

	/**
	 *
	 */
	public function listen(string $message = '', array $data): void
	{
		if ( '' === $message ) {
			$message = 'Debug in Console via Adminimize Plugin:';
		}
		$this->data[] = [$message, $data];
	}

	/**
	 *
	 */
	public function dump(): void
	{
		// Buffering.
		ob_start();
		$output = '';
		foreach($this->data as $entry ) {
			$output  = 'console.info(' . json_encode( $entry[0] ) . ');';
			$output .= 'console.log(' . json_encode( $entry[1] ) . ');';
		}

		echo sprintf('<script>%s</script>', $output);
	}
}
