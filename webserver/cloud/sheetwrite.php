<?php

$data = json_decode(file_get_contents('php://input'), true);
$temp = $data['temp'];
$hum = $data['hum'];
$press = $data['press'];
$ws = $data['ws'];
$power = $data['power'];
$db = new SQLite3('data.db');
$date = date("Y-m-d H:i:s");

$db->exec("CREATE TABLE IF NOT EXISTS data (
                    id INTEGER PRIMARY KEY AUTOINCREMENT, 
                    time TEXT,
                    temp INTEGER, 
                    hum INTEGER,
                    press INTEGER,
                    ws INTEGER,
                    power INTEGER
                    )");

$db->exec("INSERT INTO data (temp, hum, press, ws, power, time) VALUES ('$temp', '$hum', '$press', '$ws', '$power', '$date')");

/*
ESPEŠIALY for sheet convert all dots to čárky !
*/

$data = str_replace(".",",",json_decode(file_get_contents('php://input'), true));
$temp = $data['temp'];
$hum = $data['hum'];
$press = $data['press'];
$ws = $data['ws'];
$url = 'YOURURLHERE';

$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query(
            array(
                'temp' => $temp,
                'hum' => $hum,
                'press' => $press,
                'windspeed' => $ws
            )
        ),
        'timeout' => 60
    )
));

$resp = file_get_contents($url, FALSE, $context);
print_r($resp);



?>