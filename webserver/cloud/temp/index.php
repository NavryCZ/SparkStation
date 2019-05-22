<?php
	header('Content-Type: application/json; charset=utf-8');
	header('Cache-Control: max-age=0, private, must-revalidate');

    $limit = $_GET['last'];
    if (!isset($limit)){
        $limit = 1;
    }

    $db = new SQLite3('../data.db');
    $results = $db->query("SELECT * FROM data ORDER BY time DESC LIMIT $limit");

    $i = 0;
    while ($row = $results->fetchArray()) {
        $data = $row['temp'];
        $i = $i+1;
    }
    echo('{"week_number":"11","utc_offset":"+01:00","unixtime":"1552572532","timezone":"Europe/Prague","dst_until":null,"dst_from":null,"dst":false,"day_of_year":73,"day_of_week":4,"datetime":"2019-03-14T15:08:52.551955+01:00","abbreviation":"CET"}');
?> 