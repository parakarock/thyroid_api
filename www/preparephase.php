<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'nurse' || $role === 'patient') && $method === 'get_preparephase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Prepare_phase WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'update_preparephase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $prep_date = $data->prep_date;
    $method = $data->method;
    $appoint_time = $data->appoint_time;
    $period_control = $data->period_control;
    $birth_control_state = $data->birth_control_state;
    $birth_control_time = $data->birth_control_time;
    $last_period = $data->last_period;
    $last_period_amount = $data->last_period_amount;

    $sql = "UPDATE Complication_phase SET prep_date = ?, method = ?, appoint_time = ?, period_control = ?, birth_control_state = ?, birth_control_time = ?, last_period = ?, last_period_amount = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssssssi', $prep_date, $method, $appoint_time, $period_control, $birth_control_state, $birth_control_time, $last_period, $last_period_amount, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update preparephase Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'get_UTP') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM UPT_detail WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'insert_UTP') {
	$postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
	$UPT_date = $data->UPT_date;
	$UPT_result = $data->UPT_result;
    $sql = "INSERT INTO UPT_detail (idUPT_detail,person_id,rou_id,UPT_date,UPT_result) VALUES (NULL,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siss', $idCard,$round,$UPT_date,$UPT_result);
    $error = $stmt->execute();if ($error) {
        echo json_encode(array("result" => "Insert UTP Success..."));
    } else {
        echo json_encode(array("result" => "Failddddd"));
    }
}

if ($role === 'nurse' && $method === 'update_UTP') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idUPT_detail = $data->idUPT_detail;
	$idCard = $data->idcard;
    $round = $data->round;

    $UPT_date = $data->UPT_date;
    $UPT_result = $data->UPT_result;

    $sql = "UPDATE UPT_detail SET UPT_date = ?, UPT_result = ? WHERE person_id = ? AND rou_id = ? AND idUPT_detail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $UPT_date, $UPT_result, $idCard, $round,$idUPT_detail);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update preparephase Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
