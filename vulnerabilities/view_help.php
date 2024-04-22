<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] = 'Help' . $page[ 'title_separator' ].$page[ 'title' ];

if (array_key_exists ("id", $_GET) &&
	array_key_exists ("security", $_GET) &&
	array_key_exists ("locale", $_GET)) {
	$id       = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET[ 'id' ]); // Sanitize 'id'
	$security = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET[ 'security' ]); // Sanitize 'security'
	$locale   = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET[ 'locale' ]); // Sanitize 'locale'

	$help_file = $locale == 'en' ? "help.php" : "help.{$locale}.php";
	$help_path = DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/{$help_file}";

	if (file_exists($help_path)) {
		$help = file_get_contents($help_path);
	} else {
		$help = "<p>Help file not found.</p>";
	}
} else {
	$help = "<p>Not Found</p>";
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
	{$help}
</div>\n";

dvwaHelpHtmlEcho( $page );

?>
