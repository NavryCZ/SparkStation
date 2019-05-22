<?php
$temp = 20;
$hum = 66;
$pres = 1054;
$pow = 100;
$date = (new DateTime());
$db = new SQLite3('data.db');
for ($id = 1; $id <= 800; $id++){
    $temp = $temp + rand(-1,1);
    $hum = $hum + rand(-1,1);
    $pres = $pres + rand(-3,3);
    $pow = $pow + (round(rand(-55,0) / 102,0));
    $date->modify('+300 second'); 


$time = $date->format('Y-m-d H:i:s');


$db->exec("CREATE TABLE IF NOT EXISTS data (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, 
                    time TEXT,
                    temp INTEGER, 
                    hum INTEGER,
                    pres INTEGER,
                    pow INTEGER
                    )");

$db->exec("INSERT INTO data (temp, hum, pres, pow, time) VALUES ('$temp', '$hum', '$pres', '$pow','$time')");
}
    
?> 