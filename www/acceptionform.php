<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

// {
// 	"idcard": "1250100366411",
// 	"acceptionform" : "asds"
// }

if ($role === 'patient' && $method === 'update_acceptionform') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;

    $acceptionform = $data->acceptionform;

    $error = false;
    $sql = "UPDATE User_profile SET acceptionform=? WHERE person_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $acceptionform, $idCard);
    $error = $stmt->execute();
	if ($error) {
        echo json_encode(array("result" => "Update acceptionform Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
