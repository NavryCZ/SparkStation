<?php

$limit = $_GET['last'];
if (!isset($limit)){
    $limit = 10;
}

$db = new SQLite3('data.db');
$results = $db->query("SELECT * FROM data ORDER BY id DESC LIMIT $limit");
echo '<pre>';
while ($row = $results->fetchArray()) {
    printf("id= %-5d teplota= %-2.1f°C   vlhkost= %-2.1f%%   tlak= %-4.1fkPa   rychlost vetru= %-2.2fm/s baterie= %-2.1f  time= %s \n",$row['id'],$row['temp'],$row['hum'],$row['press'],$row['ws'],$row['power'],$row['time']);
}
echo '</pre>'
?>
