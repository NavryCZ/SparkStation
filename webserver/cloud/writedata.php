<?php
$temp = $_GET['temp'];
$hum = $_GET['hum'];
$pres = $_GET['pres'];
$pow = $_GET['pow'];

$db = new SQLite3('data.db');
$date = date("Y-m-d H:i:s");


$db->exec("CREATE TABLE IF NOT EXISTS data (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, 
                    time TEXT,
                    temp INTEGER, 
                    hum INTEGER,
                    pres INTEGER,
                    pow INTEGER
                    )");

$db->exec("INSERT INTO data (temp, hum, pres, pow, time) VALUES ('$temp', '$hum', '$pres', '$pow','$date')");
    
?>