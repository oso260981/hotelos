<?php
require 'vendor/autoload.php';
require 'system/Test/bootstrap.php';
$db = \Config\Database::connect();
try {
    $db->connect();
    echo "Connection Successful to " . $db->getDatabase() . "\n";
} catch (\Exception $e) {
    echo "Connection Failed: " . $e->getMessage() . "\n";
}

$db->close();
