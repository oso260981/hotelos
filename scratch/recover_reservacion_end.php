<?php
$file = 'c:/xampp/htdocs/hotelos/app/Controllers/Reservacion.php';
$content = file_get_contents($file);
$clean = preg_replace('/[^\x20-\x7E\t\n\r]/', ' ', $content);
echo substr($clean, 60000); // Print from 60KB
