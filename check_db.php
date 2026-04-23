<?php
$db = mysqli_connect('srv940.hstgr.io', 'u653032309_Hotel', 'D@nte011273', 'u653032309_hotel_pms');
if (!$db) die("Error: " . mysqli_connect_error());
$res = mysqli_query($db, "SHOW COLUMNS FROM registros LIKE 'incluir_en_reporte'");
if (mysqli_num_rows($res) > 0) echo "Column exists\n";
else echo "Column MISSING\n";
$res = mysqli_query($db, "SHOW COLUMNS FROM registros LIKE 'estado_id'");
if (mysqli_num_rows($res) > 0) echo "Column estado_id exists\n";
else echo "Column estado_id MISSING\n";

mysqli_close($db);
