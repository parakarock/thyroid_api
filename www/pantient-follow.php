<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header("Content-type: application/json; charset=UTF-8");

if (($role === 'patient' || $role === 'doctor') && $method === 'get_follow') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Patient_follow WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($error) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($resultArray, $row);
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode(array("result" => "Invalid Data of `$idCard`"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'nurse' && $method === 'update_labtest') {

}

if ($role === 'nurse' && $method === 'insert_labtest') {

}
