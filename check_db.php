<?php
$db = \Config\Database::connect();
try {
    echo "COLUMNAS OCR_REGISTROS:\n";
    $fields = $db->getFieldNames('ocr_registros');
    print_r($fields);
    
    echo "\nCOLUMNAS REGISTRO_SESIONES_FIRMA:\n";
    $fields2 = $db->getFieldNames('registro_sesiones_firma');
    print_r($fields2);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
