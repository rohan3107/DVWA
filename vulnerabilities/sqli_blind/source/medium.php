<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
    // Get input
    $id = $_POST[ 'id' ];
    $exists = false;

    switch ($_DVWA['SQLI_DB']) {
        case MYSQL:
            $id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $id ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

            // Check database
            $query  = "SELECT first_name, last_name FROM users WHERE user_id = ?;";
            try {
                $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
                mysqli_stmt_bind_param($stmt, 's', $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt); // Removed 'or die' to suppress mysql errors
            } catch (Exception $e) {
                print "There was an error.";
                exit;
            }

            $exists = false;
            if ($result !== false) {
                try {
                    $exists = (mysqli_num_rows( $result ) > 0); // The '@' character suppresses errors
                } catch(Exception $e) {
                    $exists = false;
                }
            }
            
            break;
        case SQLITE:
            global $sqlite_db_connection;
            
            $query  = "SELECT first_name, last_name FROM users WHERE user_id = :id;";
            try {
                $stmt = $sqlite_db_connection->prepare($query);
                $stmt->bindValue(':id', $id, SQLITE3_TEXT);
                $results = $stmt->execute();
                $row = $results->fetchArray();
                $exists = $row !== false;
            } catch(Exception $e) {
                $exists = false;
            }
            break;
    }

    if ($exists) {
        // Feedback for end user
        $html .= '<pre>User ID exists in the database.</pre>';
    } else {
        // Feedback for end user
        $html .= '<pre>User ID is MISSING from the database.</pre>';
    }
}

?>
