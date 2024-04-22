<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
	// Get input
	$target = $_REQUEST[ 'ip' ];

	// Validate IP address
	if (filter_var($target, FILTER_VALIDATE_IP)) {
		// Determine OS and execute the ping command.
		if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
			// Windows
			$cmd = shell_exec( 'ping  ' . escapeshellarg($target) );
		}
		else {
			// *nix
			$cmd = shell_exec( 'ping  -c 4 ' . escapeshellarg($target) );
		}
	} else {
		$cmd = "Invalid IP address.";
	}

	// Feedback for the end user
	$html .= "<pre>{$cmd}</pre>";
}

?>
