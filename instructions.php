<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/Parsedown.php';

dvwaPageStartup( array( ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Instructions' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'instructions';

$docs = array(
	'readme'         => array( 'type' => 'markdown', 'legend' => 'Read Me', 'file' => 'README.md' ),
	'PDF'            => array( 'type' => 'html' ,'legend' => 'PDF Guide', 'file' => 'docs/pdf.html' ),
	'changelog'      => array( 'type' => 'markdown', 'legend' => 'Change Log', 'file' => 'CHANGELOG.md' ),
	'copying'        => array( 'type' => 'markdown', 'legend' => 'Copying', 'file' => 'COPYING.txt' ),
);

$selectedDocId = isset( $_GET[ 'doc' ] ) ? $_GET[ 'doc' ] : '';
if( !array_key_exists( $selectedDocId, $docs ) ) {
	$selectedDocId = 'readme';
}
$readFile = $docs[ $selectedDocId ][ 'file' ];

// Validate the file path to prevent SSRF
$realBase = realpath(DVWA_WEB_PAGE_TO_ROOT);
$realUserPath = realpath($realBase . '/' . $readFile);

if ($realUserPath === false || strpos($realUserPath, $realBase) !== 0) {
    // Invalid path or file not found
    $instructions = "Invalid file path.";
} else {
    $instructions = file_get_contents($realUserPath);
}

if ($docs[ $selectedDocId ]['type'] == "markdown") {
	$parsedown = new ParseDown();
	$instructions = $parsedown->text($instructions);
}

$docMenuHtml = '';
foreach( array_keys( $docs ) as $docId ) {
	$selectedClass = ( $docId == $selectedDocId ) ? ' selected' : '';
	$docMenuHtml  .= "<span class=\"submenu_item{$selectedClass}\"><a href=\"?doc={$docId}\">{$docs[$docId]['legend']}</a></span>";
}
$docMenuHtml = "<div class=\"submenu\">{$docMenuHtml}</div>";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Instructions</h1>

	{$docMenuHtml}

	<span class=\"fixed\">
		{$instructions}
	</span>
</div>";

dvwaHtmlEcho( $page );

?>
