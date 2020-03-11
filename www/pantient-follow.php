<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
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
    $resultArray = array();
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
        echo json_encode(array("result" => "Faildsds"));
    }

}

if ($role === 'doctor' && $method === 'update_follow') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $follow_id = $data->follow_id;
    $idCard = $data->idcard;
    $round = $data->round;

    $pa_fol_date = $data->pa_fol_date;
    $pa_fol_init = $data->pa_fol_init;
    $fT4_result = $data->fT4_result;
    $TSH_result = $data->TSH_result;
    $pa_fol_anti = $data->pa_fol_anti;
    $pa_fol_anti_amount = $data->pa_fol_anti_amount;
    $pa_fol_anti_daily = $data->pa_fol_anti_daily;
    $pa_fol_beta_name = $data->pa_fol_beta_name;
    $pa_fol_bata_amount = $data->pa_fol_bata_amount;
    $pa_fol_bata_daily = $data->pa_fol_bata_daily;

    $sql = "UPDATE Patient_follow SET pa_fol_date = ?, pa_fol_init = ?, fT4_result = ?, TSH_result = ?, pa_fol_anti = ?, pa_fol_anti_amount = ?, pa_fol_anti_daily = ?, pa_fol_beta_name = ?, pa_fol_bata_amount = ?, pa_fol_bata_daily = ? WHERE person_id = ? AND rou_id = ? AND follow_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssddssissisii', $pa_fol_date, $pa_fol_init, $fT4_result, $TSH_result, $pa_fol_anti, $pa_fol_anti_amount, $pa_fol_anti_daily, $pa_fol_beta_name, $pa_fol_bata_amount, $pa_fol_bata_daily, $idCard, $round, $follow_id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update follow Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'doctor' && $method === 'insert_follow') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $pa_fol_date = $data->pa_fol_date;
    $pa_fol_init = $data->pa_fol_init;
    $fT4_result = $data->fT4_result;
    $TSH_result = $data->TSH_result;
    $pa_fol_anti = $data->pa_fol_anti;
    $pa_fol_anti_amount = $data->pa_fol_anti_amount;
    $pa_fol_anti_daily = $data->pa_fol_anti_daily;
    $pa_fol_beta_name = $data->pa_fol_beta_name;
    $pa_fol_bata_amount = $data->pa_fol_bata_amount;
    $pa_fol_bata_daily = $data->pa_fol_bata_daily;

    $error = false;
    $sql = "INSERT INTO Patient_follow (follow_id, person_id, rou_id, pa_fol_date, pa_fol_init, fT4_result, TSH_result, pa_fol_anti,
    pa_fol_anti_amount, pa_fol_anti_daily, pa_fol_beta_name, pa_fol_bata_amount, pa_fol_bata_daily) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sissddssissi', $idCard, $round, $pa_fol_date, $pa_fol_init, $fT4_result, $TSH_result, $pa_fol_anti, $pa_fol_anti_amount, $pa_fol_anti_daily, $pa_fol_beta_name, $pa_fol_bata_amount, $pa_fol_bata_daily);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Insert follow Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'doctor' && $method === 'update_follow_result') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $follow_result = $data->follow_result;

    $sql = "UPDATE Patient_Summary SET follow_result = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $follow_result, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update follow_result Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'doctor' && $method === 'update_follow_iodine') {
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
        echo json_encode(array("result" => "Update iodine_result Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'doctor' && $method === 'insert_new_phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $sql = "SELECT * FROM Patient_Summary WHERE person_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows+1;
    $sql = "INSERT INTO Patient_Summary (person_id,rou_id) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $num);
    $error = $stmt->execute();if ($error) {
        echo json_encode(array("result" => "Insert new_phase Success..."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
