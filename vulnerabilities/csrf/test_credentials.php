<?php

define('DVWA_WEB_PAGE_TO_ROOT', '../../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated'));
dvwaDatabaseConnect();
$login_state = "";

if (isset($_POST['Login'])) {

    $user = $_POST['username'];
    $user = stripslashes($user);
    $user = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $user);

    $pass = $_POST['password'];
    $pass = stripslashes($pass);
    $pass = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $pass);
    // Use password_hash() to hash the password
    // $pass = password_hash($pass, PASSWORD_DEFAULT); // This line would be used when registering a new user

    $query = "SELECT * FROM `users` WHERE user='$user';";
    $result = @mysqli_query($GLOBALS["___mysqli_ston"], $query) or die('<pre>' . mysqli_connect_error() . '.<br />Try <a href="setup.php">installing again</a>.</pre>');
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Use password_verify() to check the hashed password.
        if (password_verify($pass, $row['password'])) {
            // Login Successful...
            $login_state = "<h3 class=\"loginSuccess\">Valid password for '{$user}'</h3>";
        } else {
            // Login failed
            $login_state = "<h3 class=\"loginFail\">Wrong password for '{$user}'</h3>";
        }
    } else {
        // User not found
        $login_state = "<h3 class=\"loginFail\">User '{$user}' not found</h3>";
    }
}

$messagesHtml = messagesPopAllToHtml();
$page = dvwaPageNewGrab();

$page['title'] .= "Test Credentials";
$page['body'] .= "
        <div class=\"body_padded\">
            <h1>Test Credentials</h1>
            <h2>Vulnerabilities/CSRF</h2>
            <div id=\"code\">
                <form action=\"" . DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/csrf/test_credentials.php\" method=\"post\">
                    <fieldset>
                        " . $login_state . "
                        <label for=\"user\">Username</label><br /> <input type=\"text\" class=\"loginInput\" size=\"20\" name=\"username\"><br />
                        <label for=\"pass\">Password</label><br /> <input type=\"password\" class=\"loginInput\" AUTOCOMPLETE=\"off\" size=\"20\" name=\"password\"><br />
                        <p class=\"submit\"><input type=\"submit\" value=\"Login\" name=\"Login\"></p>
                    </fieldset>
                </form>
                {$messagesHtml}
            </div>
        </div>\n";

dvwaSourceHtmlEcho($page);

?>