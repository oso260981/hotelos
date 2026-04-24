<?php
$content = file_get_contents('app/Controllers/Reportes.php');
$trimmed = ltrim($content);
$pos = strlen($content) - strlen($trimmed);
echo "Leading spaces: " . $pos . "\n";
echo "First 200 chars of code:\n";
echo substr($trimmed, 0, 200);
echo "\n";
