<?php
require './connect.php';
require './random.php';
$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'หมอ' || $role === 'ผู้ป่วย' || $role === 'พยาบาล') && $method === 'get_patient') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $code = $data->code;
    $sql = "SELECT * FROM QRcode WHERE code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idCard = $row['owner'];
        $sql = "SELECT person_id,concat(title,firstname,' ',lastname) as fullname ,gender FROM User_profile WHERE person_id = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $idCard);
        $stmt->execute();
        $result1 = $stmt->get_result();

        $sql = "SELECT rou_id FROM Patient_Summary WHERE person_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $idCard);
        $stmt->execute();
        $result2 = $stmt->get_result();
        $resultArray1 = array();
        $resultArray2 = array();
        if ($result1->num_rows > 0 && $result2->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {
                array_push($resultArray1, $row);
            }
            while ($row = $result2->fetch_assoc()) {
                array_push($resultArray2, $row);
            }
            array_push($resultArray1, $resultArray2);
            echo json_encode($resultArray1);
        } else {
            echo json_encode(array("result" => "ไม่พบข้อมูล"));
        }
    } else {
        echo json_encode(array("result" => "QRcode ไม่ถูกต้อง"));
    }
}

if ($role === 'ผู้ป่วย' && $method === 'genarate_QRcode') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $sql = "SELECT * FROM QRcode WHERE owner = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(array("code" => $row['code']));
    } else if($idCard) {
        $sql = "INSERT INTO QRcode (owner,code,created) VALUES (?,?,CURRENT_TIMESTAMP())";
        $stmt = $conn->prepare($sql);
        $number = random_password(5);
        $stmt->bind_param('ss', $idCard, $number);
        $error = $stmt->execute();
        if ($error) {
            echo json_encode(array("code" => $number));
        } else {
            echo json_encode(array("result" => "Fail"));
        }
    }

}
