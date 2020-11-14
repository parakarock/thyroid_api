<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_bodyresult1') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Body_result1 WHERE person_id = ? AND rou_id = ?";
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

if ($role === 'หมอ' && $method === 'update_bodyresult1') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $check_date = $data->check_date;
    $sweat = $data->sweat;
    $hair_loss = $data->hair_loss;
    $body_weight = $data->body_weight;
    $heart_rate = $data->heart_rate;
    $blood_pressure_upper = $data->blood_pressure_upper;
    $blood_pressure_lower = $data->blood_pressure_lower;
    $eye_detect = $data->eye_detect;
    $eye_result = $data->eye_result;
    $doctor_name = $data->doctor_name;
    $doctor_date = $data->doctor_date;
    $doctor_file = $data->doctor_file;
    $doctor_result = $data->doctor_result;
    $treatment = $data->treatment;

    $sql = "UPDATE Body_result1 SET check_date = ?, sweat = ?, hair_loss = ?, body_weight = ?, heart_rate = ?, blood_pressure_upper = ?, blood_pressure_lower = ?, eye_detect = ?, eye_result = ?, doctor_name = ?, doctor_date = ?, doctor_file = ?, doctor_result = ?, treatment = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssddddssssssssi', $check_date, $sweat, $hair_loss, $body_weight, $heart_rate, $blood_pressure_upper, $blood_pressure_lower, $eye_detect, $eye_result, $doctor_name, $doctor_date, $doctor_file, $doctor_result, $treatment, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "บันทึกข้อมูลเสร็จสมบูรณ์"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_bodyresult2') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Body_result2 WHERE person_id = ? AND rou_id = ?";
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

if ($role === 'หมอ' && $method === 'update_bodyresult2') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $thyroid_size = $data->thyroid_size;
    $thyroid_tumor_detect = $data->thyroid_tumor_detect;
    $thyroid_tumor_size = $data->thyroid_tumor_size;
    $heart_lung_unusual = $data->heart_lung_unusual;
    $heart_lung_detail = $data->heart_lung_detail;
    $trembling_hand = $data->trembling_hand;
    $power_left_hand = $data->power_left_hand;
    $power_right_hand = $data->power_right_hand;
    $power_left_leg = $data->power_left_leg;
    $power_right_leg = $data->power_right_leg;
    $swell_shin = $data->swell_shin;
    $brittle_nail = $data->brittle_nail;
    $detail = $data->detail;

    $sql = "UPDATE Body_result2 SET thyroid_size = ?, thyroid_tumor_detect = ?, thyroid_tumor_size = ?, heart_lung_unusual = ?, heart_lung_detail = ?, trembling_hand = ?, power_left_hand = ?, power_right_hand = ?, power_left_leg = ?, power_right_leg = ?, swell_shin = ?, brittle_nail = ?, detail = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('dsdsssiiiissssi', $thyroid_size, $thyroid_tumor_detect, $thyroid_tumor_size, $heart_lung_unusual, $heart_lung_detail, $trembling_hand, $power_left_hand, $power_right_hand, $power_left_leg, $power_right_leg, $swell_shin, $brittle_nail, $detail, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "บันทึกข้อมูลเสร็จสมบูรณ์"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_thyroidScan') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Thyroid_scan WHERE person_id = ? AND rou_id = ?";
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

if ($role === 'หมอ' && $method === 'update_thyroidScan') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
	$uploadOk = 1;

    $thy_scan_image = $data->thy_scan_image;
    $thy_scan_desc = $data->thy_scan_desc;

    $sql = "UPDATE Thyroid_scan SET thy_scan_image = ?, thy_scan_desc = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $thy_scan_image, $thy_scan_desc, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("$thy_scan_image"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_thyroidUltraPic') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Thyroid_ultrasound WHERE person_id = ? AND rou_id = ?";
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

if ($role === 'หมอ' && $method === 'update_thyroidUltraPic') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $thyroid_image = $data->thyroid_image;
    $thy_ult_result = $data->thy_ult_result;

    $sql = "UPDATE Thyroid_ultrasound SET thyroid_image = ?, thy_ult_result = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $thyroid_image, $thy_ult_result, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array($thyroid_image));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_thyroidUltraMass') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
	$round = $data->round;
    $sql = "SELECT * FROM Thyroid_ult_detail WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error=$stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($error) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($resultArray, $row);
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode(array("result" => "Invalid thyroidUltraMass of `$idCard`"));
        }
    } else {
        echo json_encode(array("result" => "Faildsd"));
    } 
}

if ($role === 'หมอ' && $method === 'insert_thyroidUltraMass') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $thy_num = $data->thy_num;
    $thy_ult_date = $data->thy_ult_date;
    $thy_ult_advice = $data->thy_ult_advice;
    $thy_ult_follow_num = $data->thy_ult_follow_num;
    $thy_ult_follow_unit = $data->thy_ult_follow_unit;
    $thy_ult_fine_result = $data->thy_ult_fine_result;
    $thy_ult_surgury_desc = $data->thy_ult_surgury_desc;

    $sql = "SELECT * FROM Thyroid_ult_detail WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error=$stmt->execute();
    $result = $stmt->get_result();

    $sql = "INSERT INTO Thyroid_ult_detail (person_id, rou_id, thy_num, thy_ult_date, thy_ult_advice, thy_ult_follow_num, thy_ult_follow_unit, thy_ult_fine_result, thy_ult_surgury_desc) 
            VALUES (?,?,?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE 
            thy_ult_date = VALUES(thy_ult_date),
            thy_ult_advice = VALUES(thy_ult_advice), 
            thy_ult_follow_num = VALUES(thy_ult_follow_num), 
            thy_ult_follow_unit = VALUES(thy_ult_follow_unit), 
            thy_ult_fine_result = VALUES(thy_ult_fine_result), 
            thy_ult_surgury_desc = VALUES(thy_ult_surgury_desc)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sisssssss', $idCard, $round, $thy_num, $thy_ult_date, $thy_ult_advice, $thy_ult_follow_num, $thy_ult_follow_unit, $thy_ult_fine_result, $thy_ult_surgury_desc);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Insert thyroidUltraMass Success..."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'หมอ' && $method === 'update_thyroidUltraMass') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $thy_ult_id = $data->thy_ult_id;
    $idCard = $data->idcard;
    $round = $data->round;

    $thy_num = $data->thy_num;
    $thy_size = $data->thy_size;
    $thy_desc = $data->thy_desc;
    $thy_ult_size = $data->thy_ult_size;
    $thy_result = $data->thy_result;

    $sql = "UPDATE Thyroid_ult_detail SET thy_num = ?, thy_size = ?, thy_desc = ?, thy_ult_size = ?, thy_result = ? WHERE person_id = ? AND rou_id = ? AND thy_ult_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('idsdssii', $thy_num, $thy_size, $thy_desc, $thy_ult_size, $thy_result, $idCard, $round, $thy_ult_id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update thyroidUltraMass Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'หมอ' && $method === 'delete_thyroidUltraMass') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $thy_ult_id = $data->thy_ult_id;
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "DELETE FROM Thyroid_ult_detail WHERE person_id = ? AND rou_id = ? AND thy_ult_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $idCard, $round, $thy_ult_id);
    $error = $stmt->execute();
	if ($error) {
        echo json_encode(array("result" => "Delete thyroidUltraMass Success..."));
    } else {
        echo json_encode(array("result" => "Delete thyroidUltraMass Fail..."));
    }
}
