<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$cookie_value = time();
	setcookie("dvwaSession", $cookie_value, 0, "", "", true, true);
}
?>

