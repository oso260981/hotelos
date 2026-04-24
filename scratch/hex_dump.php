<?php
$content = file_get_contents('app/Controllers/Reportes.php');
echo "Hex: " . bin2hex(substr($content, 0, 100)) . "\n";
echo "Total length: " . strlen($content) . "\n";
