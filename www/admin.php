<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if ($role === 'admin' && $method === 'insert_hospital') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);

    $h_id = $data->h_id;
    $hos_name = $data->hos_name;
    $address = $data->address;
    $phone = $data->phone;
    $freeT3_standard = $data->freeT3_standard;
    $freeT4_standard = $data->freeT4_standard;
    $TSH_standard = $data->TSH_standard;
    $TRAb_standard = $data->TRAb_standard;

    $error = false;
    $sql = "INSERT INTO Hospital (id, h_id, hos_name, address, phone, freeT3_standard,
    freeT4_standard, TSH_standard, TRAb_standard) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssdddd', $h_id, $hos_name, $address, $phone, $freeT3_standard, $freeT4_standard, $TSH_standard, $TRAb_standard);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Insert hospital Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'admin' && $method === 'update_hospital') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $id = $data->id;

    $h_id = $data->h_id;
    $hos_name = $data->hos_name;
    $address = $data->address;
    $phone = $data->phone;
    $freeT3_standard = $data->freeT3_standard;
    $freeT4_standard = $data->freeT4_standard;
    $TSH_standard = $data->TSH_standard;
    $TRAb_standard = $data->TRAb_standard;

    $error = false;
    $sql = "UPDATE Hospital SET h_id=? ,hos_name=?,address=?,phone=?,freeT3_standard=?,freeT4_standard=?,TSH_standard=?,TRAb_standard=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssddddi', $h_id, $hos_name, $address, $phone, $freeT3_standard, $freeT4_standard, $TSH_standard, $TRAb_standard, $id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Update hospital Success.."));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'admin' && $method === 'delete_hospital') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $id = $data->id;
    $sql = "DELETE FROM Hospital WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "Delete hospital Success..."));
    } else {
        echo json_encode(array("result" => "Delete thyroidUltraMass Fail..."));
    }
}

if (($role === 'nurse' || $role === 'admin') && $method === 'get_hospital') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $sql = "SELECT * FROM Hospital";
    $result = $conn->query($sql);

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

if ($role === 'admin' && $method === 'insert_user') {
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

    $patient = $data->patient;
    $doctor = $data->doctor;
    $nurse = $data->nurse;

    $sql = "SELECT * FROM User_profile WHERE person_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array("result" => "$idCard is registered."));
    } else {
        $sql = "INSERT INTO User_profile (person_id,title,firstname,lastname,birthdate,gender,nationality,status,phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssss', $idCard, $title, $firstname, $lastname, $dof, $gender, $national, $status, $phone);
        $error = false;
        $error = $stmt->execute();
        if ($error) {
            if ($patient) {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'ผู้ป่วย', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {
                    $sql = "INSERT INTO Patient_Summary (person_id,rou_id,follow_result,iodine_result) VALUES (?,'1',NULL,NULL)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $idCard);
                    $error = $stmt->execute();
                    if ($error) {
                        $sql = "UPDATE Hospital_send SET from_h_id = ?, to_h_id =?, Hos_base_h_id=? , from_hn = ?, to_hn = ?, Hos_base_hn = 'โรงพยาบาลบูรพา' WHERE person_id = ? AND rou_id ='1' ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('ssssss', $from_id, $to_id, $Hos_base_id, $from_name, $to_name, $idCard);
                        $error = $stmt->execute();
                    } else {
                        echo json_encode(array("result" => "Fail"));
                    }
                } else {
                    echo json_encode(array("result" => "Fail"));
                }
            }
            if ($doctor) {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'หมอ', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
            }
            if ($nurse) {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'พยาบาล', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
            }
        } else {
            echo json_encode(array("result" => "Fail"));
        }
    }
}
