<?php
$content = file_get_contents('app/Controllers/Reportes.php');
echo substr($content, 0, 1000);
echo "\n--- Length: " . strlen($content) . " ---\n";
