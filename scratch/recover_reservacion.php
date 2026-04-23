<?php
$file = 'c:/xampp/htdocs/hotelos/app/Controllers/Reservacion.php';
$content = file_get_contents($file);
// Remove non-printable characters except newlines and tabs
$clean = preg_replace('/[^\x20-\x7E\t\n\r]/', ' ', $content);
echo substr($clean, 0, 10000); // Print first 10KB
