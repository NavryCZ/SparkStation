<?php

    $limit = $_GET['last'];
    if (!isset($limit)){
        $limit = 48;
    }

    $db = new SQLite3('data.db');
    $results = $db->query("SELECT * FROM data ORDER BY time DESC LIMIT $limit");

    $i = 0;
    while ($row = $results->fetchArray()) {
        $charttime = DateTime::createFromFormat('Y-m-d H:i:s', $row['time']);
        $time = $charttime->format( 'H:i' );
        $data[] = array(
            'id'=>$i, 
            'time'=>$time,
            'temp' => $row['temp'], 
            'hum' => $row['hum'], 
            'press' => $row['press'],
            'power' => $row['power']
        );
        $i = $i+1;
    }
    rsort($data);
    echo(json_encode($data));
?> 