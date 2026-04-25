<?php
$db = \Config\Database::connect();
$db->query("ALTER TABLE ocr_registros ADD COLUMN IF NOT EXISTS firma_path VARCHAR(255) NULL AFTER es_menor");
echo "Columna firma_path verificada/añadida.";
