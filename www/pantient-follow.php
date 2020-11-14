<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_iodine') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Patient_follow WHERE person_id = ? AND rou_id = ? AND pa_fol = 'iodine' ORDER BY follow_id  ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($error) {
        if ($result->num_rows > 0) {
            $count = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                $row["index"] = $count;
                array_push($resultArray, $row);
                $count--;
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode(array("result" => "Fail"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_follow') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Patient_follow WHERE person_id = ? AND rou_id = ? AND pa_fol = 'follow' ORDER BY follow_id  DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($error) {
        if ($result->num_rows > 0) {
            $count = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                $row["index"] = $count;
                array_push($resultArray, $row);
                $count--;
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode(array("result" => "Fail"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'หมอ' && $method === 'update_follow') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $follow_id = $data->follow_id;
    $idCard = $data->idcard;
    $round = $data->round;

    $pa_fol_date = $data->pa_fol_date;
    $pa_fol_result = $data->pa_fol_result;
    $pa_fol = $data->pa_fol;
    $ttf3_tt3 = $data->ttf3_tt3;
    $fT4_result = $data->fT4_result;
    $TSH_result = $data->TSH_result;
    $pa_fol_anti = $data->pa_fol_anti;
    $pa_fol_anti_amount = $data->pa_fol_anti_amount;
    $pa_fol_anti_daily = $data->pa_fol_anti_daily;
    $pa_fol_beta = $data->pa_fol_beta;
    $pa_fol_beta_amount = $data->pa_fol_beta_amount;
    $pa_fol_beta_daily = $data->pa_fol_beta_daily;

    $sql = "UPDATE Patient_follow SET pa_fol_date = ?, pa_fol_result = ?, pa_fol = ?, ttf3_tt3 = ?, fT4_result = ?, TSH_result = ?, pa_fol_anti = ?, pa_fol_anti_amount = ?, pa_fol_anti_daily = ?, pa_fol_beta = ?, pa_fol_beta_amount = ?, pa_fol_beta_daily = ? WHERE person_id = ? AND rou_id = ? AND follow_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdddsddsddsii', $pa_fol_date, $pa_fol_result, $pa_fol, $ttf3_tt3, $fT4_result, $TSH_result, $pa_fol_anti, $pa_fol_anti_amount, $pa_fol_anti_daily, $pa_fol_beta, $pa_fol_beta_amount, $pa_fol_beta_daily, $idCard, $round, $follow_id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'หมอ' && $method === 'insert_follow') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $pa_fol_date = $data->pa_fol_date;
    $pa_fol_result = $data->pa_fol_result;
    $pa_fol = $data->pa_fol;
    $ttf3_tt3 = $data->ttf3_tt3;
    $fT4_result = $data->fT4_result;
    $TSH_result = $data->TSH_result;
    $pa_fol_anti = $data->pa_fol_anti;
    $pa_fol_anti_amount = $data->pa_fol_anti_amount;
    $pa_fol_anti_daily = $data->pa_fol_anti_daily;
    $pa_fol_beta = $data->pa_fol_beta;
    $pa_fol_beta_amount = $data->pa_fol_beta_amount;
    $pa_fol_beta_daily = $data->pa_fol_beta_daily;

    $error = false;
    $sql = "INSERT INTO Patient_follow (follow_id, person_id, rou_id, pa_fol_date, pa_fol_result,pa_fol, ttf3_tt3, fT4_result, TSH_result, pa_fol_anti,
    pa_fol_anti_amount, pa_fol_anti_daily, pa_fol_beta, pa_fol_beta_amount, pa_fol_beta_daily) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sisssdddsddsdd', $idCard, $round, $pa_fol_date, $pa_fol_result, $pa_fol, $ttf3_tt3, $fT4_result, $TSH_result, $pa_fol_anti, $pa_fol_anti_amount, $pa_fol_anti_daily, $pa_fol_beta, $pa_fol_beta_amount, $pa_fol_beta_daily);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'ผู้ป่วย' || $role === 'หมอ') && $method === 'get_summary') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT a.volume_result as volume_result,a.iodine_result as iodine_result,b.prep_date as prep_date,b.end_date as end_date,b.method as method FROM Patient_Summary as a
INNER JOIN Prepare_phase as b
ON a.person_id = b.person_id and a.rou_id = b.rou_id WHERE a.person_id = ? AND a.rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($error) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo json_encode(array("result" => "Fail"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'หมอ' && $method === 'update_volume_result') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $volume_result = $data->volume_result;

    $sql = "UPDATE Patient_Summary SET volume_result = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('dsi', $volume_result, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'หมอ' && $method === 'update_follow_iodine') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $iodine_result = $data->iodine_result;

    $sql = "UPDATE Patient_Summary SET iodine_result = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $iodine_result, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'หมอ' && $method === 'insert_new_phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $sql = "SELECT * FROM Patient_Summary WHERE person_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows + 1;
    $sql = "INSERT INTO Patient_Summary (person_id,rou_id) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $num);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "เพิ่มการรักษาครั้งที่ $num สำเร็จ","roundUpdate" =>  $num));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
