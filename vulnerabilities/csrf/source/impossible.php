<?php

if( isset( $_GET[ 'Change' ] ) ) {
    // Check Anti-CSRF token
    checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

    // Get input
    $pass_curr = $_GET[ 'password_current' ];
    $pass_new  = $_GET[ 'password_new' ];
    $pass_conf = $_GET[ 'password_conf' ];

    // Sanitise current password input
    $pass_curr = stripslashes( $pass_curr );
    $pass_curr = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass_curr ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

    // Check that the current password is correct
    $data = $db->prepare( 'SELECT password FROM users WHERE user = (:user) LIMIT 1;' );
    $current_user = dvwaCurrentUser();
    $data->bindParam( ':user', $current_user, PDO::PARAM_STR );
    $data->execute();
    $user = $data->fetch(PDO::FETCH_ASSOC);

    // Verify the current password
    if (password_verify($pass_curr, $user['password'])) {
        // Do both new passwords match?
        if ($pass_new == $pass_conf) {
            // It does!
            $pass_new = stripslashes($pass_new);
            $pass_new = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $pass_new) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
            $pass_new = password_hash($pass_new, PASSWORD_DEFAULT); // Use password_hash

            // Update database with new password
            $data = $db->prepare('UPDATE users SET password = (:password) WHERE user = (:user);');
            $data->bindParam(':password', $pass_new, PDO::PARAM_STR);
            $current_user = dvwaCurrentUser();
            $data->bindParam(':user', $current_user, PDO::PARAM_STR);
            $data->execute();

            // Feedback for the user
            $html .= "<pre>Password Changed.</pre>";
        } else {
            // Issue with passwords matching
            $html .= "<pre>Passwords did not match or current password incorrect.</pre>";
        }
    } else {
        // Current password is not correct
        $html .= "<pre>Current password is incorrect.</pre>";
    }
}

// Generate Anti-CSRF token
generateSessionToken();

?>