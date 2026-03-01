<?php

session_start();

//reads data given via fetch and turns it into php array
$data = json_decode(file_get_contents('php://input'), true);

//checks if the key and value exist
if (isset($data['key'], $data['value'])) {
    //proceeds to save data to session
    $_SESSION[$data['key']] = $data['value'];

    //sends response that everything is good
    http_response_code(200);
} else {
    //sends response that something is wrong
    http_response_code(400);
    echo json_encode(["error" => "Key or value not set"]);
}