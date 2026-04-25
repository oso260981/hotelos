<?php

$db = \Config\Database::connect();
$forge = \Config\Database::forge();

$fields = [
    'id' => [
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
        'auto_increment' => true,
    ],
    'tablet_id' => [
        'type'       => 'VARCHAR',
        'constraint' => '50',
        'default'    => 'TABLET_01',
    ],
    'ocr_registro_id' => [
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => true,
        'null'       => true,
    ],
    'status' => [
        'type'       => 'ENUM',
        'constraint' => ['WAIT', 'READY'],
        'default'    => 'WAIT',
    ],
    'updated_at' => [
        'type' => 'DATETIME',
        'null' => true,
    ],
];

$forge->addField($fields);
$forge->addPrimaryKey('id');
$forge->addUniqueKey('tablet_id');
$forge->createTable('registro_sesiones_firma', true);

// Insert initial record
$db->table('registro_sesiones_firma')->ignore(true)->insert([
    'id' => 1,
    'tablet_id' => 'TABLET_01',
    'status' => 'WAIT'
]);

echo "Tabla registro_sesiones_firma creada correctamente.";
