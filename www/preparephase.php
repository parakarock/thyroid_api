<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'พยาบาล' || $role === 'ผู้ป่วย') && $method === 'get_preparephase') {
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

if ($role === 'พยาบาล' && $method === 'update_preparephase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $prep_date = $data->prep_date;
    $end_date = $data->end_date;
    $method = $data->method;

    $sql = "UPDATE Prepare_phase SET prep_date = ?, end_date = ?, method = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $prep_date, $end_date, $method, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
if ($role === 'พยาบาล' && $method === 'update_period') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $birth_control_name = $data->birth_control_name;
    $birth_control_state = $data->birth_control_state;
    $birth_control_time = $data->birth_control_time;
    $last_period = $data->last_period;
    $last_period_amount = $data->last_period_amount;

    $sql = "UPDATE Prepare_phase SET  birth_control_name = ?, birth_control_state = ?, birth_control_time = ?, last_period = ?, last_period_amount = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdssssi', $birth_control_name, $birth_control_state, $birth_control_time, $last_period, $last_period_amount, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'พยาบาล' || $role === 'ผู้ป่วย') && $method === 'get_UTP') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM UPT_detail WHERE person_id = ? AND rou_id = ? ORDER BY UPT_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'พยาบาล' && $method === 'insert_UTP') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $UPT_date = $data->UPT_date;
    $UPT_result = $data->UPT_result;
    $sql = "INSERT INTO UPT_detail (idUPT_detail,person_id,rou_id,UPT_date,UPT_result) VALUES (NULL,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siss', $idCard, $round, $UPT_date, $UPT_result);
    $error = $stmt->execute();if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'พยาบาล' && $method === 'update_UTP') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idUPT_detail = $data->idUPT_detail;
    $idCard = $data->idcard;
    $round = $data->round;

    $UPT_date = $data->UPT_date;
    $UPT_result = $data->UPT_result;

    $sql = "UPDATE UPT_detail SET UPT_date = ?, UPT_result = ? WHERE person_id = ? AND rou_id = ? AND idUPT_detail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssii', $UPT_date, $UPT_result, $idCard, $round, $idUPT_detail);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'พยาบาล' && $method === 'update_control') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idUPT_detail = $data->idUPT_detail;
    $idCard = $data->idcard;
    $round = $data->round;

    $period_control = $data->period_control;

    $sql = "UPDATE Prepare_phase SET period_control = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $period_control, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'พยาบาล' || $role === 'หมอ') && $method === 'get_prep') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $date = $data->date;

    $sql = "SELECT DISTINCT CONCAT(b.title,b.firstname,' ',b.lastname) as name,b.phone,c.prep_date,c.end_date,c.method FROM Patient_Summary as a
            INNER JOIN User_profile as b
            ON a.person_id = b.person_id
            INNER JOIN Prepare_phase as c
            ON c.person_id = b.person_id
            WHERE c.prep_date = ? or c.end_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $date, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
