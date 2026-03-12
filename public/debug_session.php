<?php
session_start();
echo '<pre>';
echo "PHP Session ID: " . session_id() . "\n";
echo "Session data:\n";
print_r($_SESSION);
echo "\nCI Session files:\n";
$dir = 'C:/xampp81/htdocs/lrhrims/writable/session/';
foreach (glob($dir . 'ci_session*') as $f) {
    echo basename($f) . " - " . date('Y-m-d H:i:s', filemtime($f)) . " - " . filesize($f) . " bytes\n";
}
echo '</pre>';
