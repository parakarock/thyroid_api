<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header("Content-type: application/json; charset=UTF-8");

// ขอข้อมูล profile เรียก get_profile ส่ง role
if (($role === 'nurse' || $role === 'patient' || $role === 'doctor') && $method === 'get_init-phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM User_profile WHERE person_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $stmt->execute();
    $result1 = $stmt->get_result();

    $sql = "SELECT * FROM Hospital_send WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $resultArray = array();

    if ($result1->num_rows > 0 && $result2->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        while ($row = $result2->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

// if (($role === 'nurse' || $role === 'admin') && $method === 'update_profile') {
//     echo "update_profile";
// }

if (($role === 'nurse' || $role === 'patient' || $role === 'doctor') && $method === 'get_init-phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Init_symp WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

// if ($role === 'nurse' && $method === 'update_init-phase') {
//     echo "update_init-phase";
// }
if (($role === 'nurse' || $role === 'patient' || $role === 'doctor') && $method === 'get_sym-phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Symp_phase WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

// if ($role === 'nurse' && $method === 'update_sym-phase') {
//     echo "update_sym-phase";
// }

if (($role === 'nurse' || $role === 'patient' || $role === 'doctor') && $method === 'get_mineral_therapy') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Thyroid_mineral_therapy WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

// if ($role === 'nurse' && $method === 'update_mineral_therapy') {
//     echo "update_mineral_therapy";
// }

if (($role === 'nurse' || $role === 'patient' || $role === 'doctor') && $method === 'get_mineral_history') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Mineral_history WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

// if ($role === 'nurse' && $method === 'update_mineral_history') {

// }

if (($role === 'patient' || $role === 'doctor') && $method === 'get_complication') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Complication_phase WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

// if ($role === 'doctor' && $method === 'update_complication') {

// }
