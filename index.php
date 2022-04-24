<?php
$db = new mysqli(getenv('db_host'), getenv('db_user'), getenv('db_pass'), getenv('db_name'), getenv('db_port'));
$path = explode('/', $_GET['path']);

if (count($path) == 2 && $path[1] == 'api' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = [];
    $result = $db->query("SELECT * FROM `records`");
    while ($row = $result->fetch_array()) {
        $response[] = [
            "id" => $row['id'],
            "value" => $row['value'],
            "state" => $row['state']
        ];
    }
    die(json_encode($response));
}

if (count($path) == 2 && $path[1] == 'api' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $value = $db->real_escape_string($json->value);

    //TODO: switch to safe request
    $db->query("INSERT INTO `records` (`value`, `state`) '$value', 'undone'");
    if ($db->affected_rows == 1) {
        http_response_code(201);
        die();
    }
    else {
        http_response_code(400);
        die();
    }
}

if (count($path) == 4 && $path[1] == 'api' && is_numeric($path[2]) && $path[3] == 'done' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $id = $path[2]*1;
    $db->query("UPDATE `records` SET `state` = 'done' WHERE `id` = $id");
    if ($db->affected_rows == 1) {
        http_response_code(200);
        die();
    }
    else {
        http_response_code(404);
        die();
    }
}

if (count($path) == 4 && $path[1] == 'api' && is_numeric($path[2]) && $path[3] == 'undone' && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    $id = $path[2]*1;
    $db->query("UPDATE `records` SET `state` = 'undone' WHERE `id` = $id");
    if ($db->affected_rows == 1) {
        http_response_code(200);
        die();
    }
    else {
        http_response_code(404);
        die();
    }
}

if (count($path) == 3 && $path[1] == 'api' && is_numeric($path[2]) && $_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = $path[2]*1;
    $db->query("DELETE FROM `records` WHERE `id` = $id");
    if ($db->affected_rows == 1) {
        http_response_code(204);
        die();
    }
    else {
        http_response_code(404);
        die();
    }
}

if (count($path) == 2 && $path[1] == 'health') {
    try {
       $db->query("SELECT 1")->fetch_array();
    } catch(\Throwable $e) {
        die("NOT OK");
    };
    die("OK");
}