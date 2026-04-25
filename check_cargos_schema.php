<?php
$c = mysqli_connect('srv940.hstgr.io', 'u653032309_Hotel', 'D@nte011273', 'u653032309_hotel_pms');
$res = mysqli_query($c, "DESCRIBE registro_cargos");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
mysqli_close($c);
