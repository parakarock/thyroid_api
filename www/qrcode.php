<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'guest' ) && $method === 'get_patient') {
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