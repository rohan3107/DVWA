<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (function_exists('random_bytes')) {
        $bytes = random_bytes(16);
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $bytes = openssl_random_pseudo_bytes(16);
    } else {
        // Consider throwing an exception or error because a secure source of randomness is not available
        $bytes = mt_rand(); // As a last resort, fall back to mt_rand (not recommended)
    }
    $cookie_value = sha1(bin2hex($bytes) . time() . "Impossible");
    setcookie("dvwaSession", $cookie_value, time()+3600, "/vulnerabilities/weak_id/", $_SERVER['HTTP_HOST'], true, true);
}
?>
