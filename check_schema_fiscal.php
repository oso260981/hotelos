<?php
$db = \Config\Database::connect();
$tables = ['registros', 'Perfiles_Fiscales'];
foreach ($tables as $table) {
    echo "Table: $table\n";
    $fields = $db->getFieldData($table);
    foreach ($fields as $field) {
        echo "- {$field->name} ({$field->type})\n";
    }
    echo "\n";
}
