<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

// ขอข้อมูล profile เรียก get_profile ส่ง role
if (($role === 'nurse' || $role === 'patient' || $role === 'doctor') && $method === 'get_profile') {
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

if (($role === 'nurse' || $role === 'admin') && $method === 'update_profile') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $title = $data->title;
    $firstname = $data->firstname;
    $lastname = $data->lastname;
    $dof = $data->dof;
    $gender = $data->gender;
    $national = $data->national;
    $status = $data->status;
    $phone = $data->phone;
    $from_id = $data->from_id;
    $to_id = $data->to_id;
    $Hos_base_h_id = $data->Hos_base_id;
    $from_name = $data->from_name;
    $to_name = $data->to_name;
    $error = false;
    $sql = "UPDATE User_profile SET title=?,firstname=?,lastname=?,birthdate=?,gender=?,nationality=?,status=?,phone=? WHERE person_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssss', $title, $firstname, $lastname, $dof, $gender, $national, $status, $phone, $idCard);
    $error = $stmt->execute();
    if ($error) {
        $sql = "UPDATE Hospital_send SET from_h_id = ?, to_h_id =?, Hos_base_h_id=? , from_hn = ?, to_hn = ? WHERE person_id = ? AND rou_id ='1' ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $from_id, $to_id, $Hos_base_id, $from_name, $to_name, $idCard);
        $error = $stmt->execute();
        if ($error) {
            echo json_encode(array("result" => "Update profile Success.."));
        } else {
            echo json_encode(array("result" => "Fail"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

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
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'update_init-phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $frustration = $data->frustration;
    $eat_a_lot = $data->eat_a_lot;
    $feel_hot = $data->feel_hot;
    $fast_heartbeat = $data->fast_heartbeat;
    $shaking_hand = $data->shaking_hand;
    $goiter = $data->goiter;
    $thyroid_lump = $data->thyroid_lump;
    $bulging_eye = $data->bulging_eye;
    $digest_3 = $data->digest_3;
    $lose_weight = $data->lose_weight;
    $weak_arm = $data->weak_arm;
    $few_period = $data->few_period;
    $disease_name = $data->disease_name;

    $sql = "UPDATE Init_symp SET frustration = ?, eat_a_lot = ?, feel_hot = ?, fast_heartbeat = ?, shaking_hand = ?, goiter = ?, thyroid_lump = ?, bulging_eye = ?, digest_3 = ?, lose_weight = ?, weak_arm = ?, few_period = ?, disease_name = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssssssssi', $frustration, $eat_a_lot, $feel_hot, $fast_heartbeat, $shaking_hand, $goiter, $thyroid_lump, $bulging_eye, $digest_3, $lose_weight, $weak_arm, $few_period, $disease_name, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update init-phase Success.."));
    } else {
        echo json_encode(array("result" => $error->error_reporting));
    }
}

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
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'update_sym-phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $stress = $data->stress;
    $hard_work = $data->hard_work;
    $night_work_hour = $data->night_work_hour;
    $overtime_hour = $data->overtime_hour;
    $sleep_less_than_4 = $data->sleep_less_than_4;
    $pregnant = $data->pregnant;
    $toxic_womb = $data->toxic_womb;
    $smoking_amount = $data->smoking_amount;
    $smoking_time = $data->smoking_time;
    $smoking_stop = $data->smoking_stop;
    $no_risk_factor = $data->no_risk_factor;
    $relative_toxic_thyroid = $data->relative_toxic_thyroid;
    $relative_low_thyroid = $data->relative_low_thyroid;

    $sql = "UPDATE Symp_phase SET stress = ?, hard_work = ?, night_work_hour = ?, overtime_hour = ?, sleep_less_than_4 = ?, pregnant = ?, toxic_womb = ?, smoking_amount = ?, smoking_time = ?, smoking_stop = ?, no_risk_factor = ?, relative_toxic_thyroid = ?, relative_low_thyroid = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssddsssdddssssi', $stress, $hard_work, $night_work_hour, $overtime_hour, $sleep_less_than_4, $pregnant, $toxic_womb, $smoking_amount, $smoking_time, $smoking_stop, $no_risk_factor, $relative_toxic_thyroid, $relative_low_thyroid, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update sym-phase Success.."));
    } else {
        echo json_encode(array("result" => $error->error_reporting));
    }

}

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
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'update_mineral_therapy') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $therapy_1 = $data->therapy_1;
    $therapy_2 = $data->therapy_2;
    $therapy_3 = $data->therapy_3;
    $therapy_4 = $data->therapy_4;
    $therapy_5 = $data->therapy_5;
    $therapy_6 = $data->therapy_6;
    $therapy_7 = $data->therapy_7;
    $therapy_8 = $data->therapy_8;

    $sql = "UPDATE Thyroid_mineral_therapy SET therapy_1 = ?, therapy_2 = ?, therapy_3 = ?, therapy_4 = ?, therapy_5 = ?, therapy_6 = ?, therapy_7 = ?, therapy_8 = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssi', $therapy_1, $therapy_2, $therapy_3, $therapy_4, $therapy_5, $therapy_6, $therapy_7, $therapy_8, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update mineral_therapy Success.."));
    } else {
        echo json_encode(array("result" => $error->error_reporting));
    }
}

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
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'nurse' && $method === 'update_mineral_history') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $did = $data->did;
    $hospital = $data->hospital;
    $therapy_date = $data->therapy_date;
    $volume = $data->volume;

    $sql = "UPDATE Mineral_history SET did = ?, hospital = ?, therapy_date = ?, volume = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdsi', $did, $hospital, $therapy_date, $volume, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update mineral_history Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

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
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'doctor' && $method === 'update_complication') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;

    $month = $data->month;
    $year = $data->year;
    $comp_status = $data->comp_status;
    $comp_name = $data->comp_name;
    $comp_anit_thy_name = $data->comp_anit_thy_name;
    $comp_anit_thy_amount = $data->comp_anit_thy_amount;
    $comp_anit_thy_daily = $data->comp_anit_thy_daily;
    $comp_beta_name = $data->comp_beta_name;
    $comp_beta_amount = $data->comp_beta_amount;
    $comp_beta_daily = $data->comp_beta_daily;
    $comp_indication = $data->comp_indication;

    $sql = "UPDATE Complication_phase SET month = ?, year = ?, comp_status = ?, comp_name = ?, comp_anit_thy_name = ?, comp_anit_thy_amount = ?, comp_anit_thy_daily = ?, comp_beta_name = ?, comp_beta_amount = ?, comp_beta_daily = ?, comp_indication = ? WHERE person_id = ? AND rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssssssi', $month, $year, $comp_status, $comp_name, $comp_anit_thy_name, $comp_anit_thy_amount, $comp_anit_thy_daily, $comp_beta_name, $comp_beta_amount, $comp_beta_daily, $comp_indication, $idCard, $round);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update complication Success.."));
    } else {
        echo json_encode(array("result" => $error->error_reporting));
    }
}
