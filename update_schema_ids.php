<?php
$c = mysqli_connect('srv940.hstgr.io', 'u653032309_Hotel', 'D@nte011273', 'u653032309_hotel_pms');
mysqli_query($c, "ALTER TABLE registro_sesiones_firma ADD COLUMN IF NOT EXISTS registro_id INT NULL AFTER ocr_registro_id");
mysqli_query($c, "ALTER TABLE registro_sesiones_firma ADD COLUMN IF NOT EXISTS huesped_id INT NULL AFTER registro_id");

// También asegurar que registro y huesped tengan firma_path
mysqli_query($c, "ALTER TABLE registro ADD COLUMN IF NOT EXISTS firma_path VARCHAR(255) NULL");
mysqli_query($c, "ALTER TABLE huesped ADD COLUMN IF NOT EXISTS firma_path VARCHAR(255) NULL");

echo "Schema actualizado.";
mysqli_close($c);
