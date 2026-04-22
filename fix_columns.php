<?php
$db = mysqli_connect('srv940.hstgr.io', 'u653032309_Hotel', 'D@nte011273', 'u653032309_hotel_pms');
if (!$db) die("Error: " . mysqli_connect_error());

// Check if column exists
$res = mysqli_query($db, "SHOW COLUMNS FROM registros LIKE 'incluir_en_reporte'");
if (mysqli_num_rows($res) == 0) {
    echo "Adding column incluir_en_reporte...\n";
    $ok = mysqli_query($db, "ALTER TABLE registros ADD COLUMN incluir_en_reporte TINYINT(1) DEFAULT 0");
    if ($ok) echo "Column added successfully.\n";
    else echo "Error adding column: " . mysqli_error($db) . "\n";
} else {
    echo "Column incluir_en_reporte already exists.\n";
}

// Check if estado_id exists
$res = mysqli_query($db, "SHOW COLUMNS FROM registros LIKE 'estado_id'");
if (mysqli_num_rows($res) == 0) {
    echo "Adding column estado_id...\n";
    $ok = mysqli_query($db, "ALTER TABLE registros ADD COLUMN estado_id INT DEFAULT 2");
    if ($ok) echo "Column estado_id added successfully.\n";
    else echo "Error adding column estado_id: " . mysqli_error($db) . "\n";
} else {
    echo "Column estado_id already exists.\n";
}
