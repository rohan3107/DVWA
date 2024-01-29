<?php

// ... [rest of the code remains unchanged] ...

if ($change) {
	// Check Anti-CSRF token
	checkToken( $token, $_SESSION[ 'session_token' ], 'index.php' );

	// Do the passwords match?
	if( $pass_new == $pass_conf ) {
		// They do!
		$pass_new = mysqli_real_escape_string ($GLOBALS["___mysqli_ston"], $pass_new);
		
		// Use password_hash() instead of md5()
		$pass_new = password_hash($pass_new, PASSWORD_DEFAULT);

		// Update the database
		$current_user = dvwaCurrentUser();
		$insert = "UPDATE `users` SET password = '" . $pass_new . "' WHERE user = '" . $current_user . "';";
		$result = mysqli_query($GLOBALS["___mysqli_ston"],  $insert );

		// Feedback for the user
		$return_message = "Password Changed.";
	}
	else {
		// Issue with passwords matching
		$return_message = "Passwords did not match.";
	}

	mysqli_close($GLOBALS["___mysqli_ston"]);

	if ($request_type == "json") {
		generateSessionToken();
		header ("Content-Type: application/json");
		print json_encode (array("Message" =>$return_message));
		exit;
	} else {
		$html .= "<pre>" . $return_message . "</pre>";
	}
}

// Generate Anti-CSRF token
generateSessionToken();

?>