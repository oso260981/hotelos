<?php
$db = mysqli_connect('localhost', 'root', '', 'hotelos');
$res = mysqli_query($db, 'DESCRIBE salidas_clientes');
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}

mysqli_close($db);
