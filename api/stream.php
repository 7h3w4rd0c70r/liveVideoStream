<?php

include_once './connect.php';

switch ($_POST['event']) {
    case 'CLIENT CONNECTED':
        mysql_query($conn, 'INSERT INTO hosts(hostId,userId,streamName,category) VALUES (' . $_POST['hostId'] . ',' . $_POST['userId'] . ',' . $_POST['streamName'] . ',' . $_POST['category'] . ')');
        echo json_encode(array(
            'status' => 'ok'
        ));
    break;
    default:
    return;
}
