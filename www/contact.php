<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'แอดมิน' || $role === 'พยาบาล' || $role === 'ผู้ป่วย' || $role === 'หมอ' || $role === 'guest') && $method === 'get_contact') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);

    $sql = "SELECT * FROM Contact ORDER BY contact_level ASC";
    $stmt = $conn->prepare($sql);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($error) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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

if ($role === 'แอดมิน' && $method === 'update_contact') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $contact_id = $data->contact_id;
    $contact_title = $data->contact_title;
    $contact_fname = $data->contact_fname;
    $contact_lname = $data->contact_lname;
    $contact_position = $data->contact_position;
    $contact_department = $data->contact_department;
    $contact_hospital = $data->contact_hospital;
    $contact_level = $data->contact_level;
    $path_profile = $data->path_profile;

    $sql = "SELECT * FROM Contact WHERE contact_level = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $contact_level);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($error) {
        if ($result->num_rows == 0) {
            $sql = "UPDATE Contact SET contact_title=?, contact_fname=?, contact_lname=?, contact_position=?,
                    contact_department=?, contact_hospital=?, contact_level=?, path_profile=?  WHERE contact_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssisi', $contact_title, $contact_fname, $contact_lname, $contact_position, $contact_department, $contact_hospital, $contact_level, $path_profile, $contact_id);
            $error = $stmt->execute();
            if ($error) {
                echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
            } else {
                echo json_encode(array("result" => "Fail"));
            }
        } else {
            echo json_encode(array("result" => "ลำดับซ้ำไม่สามาถบันทึกได้"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'แอดมิน' && $method === 'insert_contact') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $contact_title = $data->contact_title;
    $contact_fname = $data->contact_fname;
    $contact_lname = $data->contact_lname;
    $contact_position = $data->contact_position;
    $contact_department = $data->contact_department;
    $contact_hospital = $data->contact_hospital;
    $contact_level = $data->contact_level;
    $path_profile = $data->path_profile;

    $sql = "SELECT * FROM Contact WHERE contact_level = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $contact_level);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($error) {
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO Contact (contact_id, contact_title, contact_fname, contact_lname, contact_position, contact_department, contact_hospital, contact_level, path_profile) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssis', $contact_title, $contact_fname, $contact_lname, $contact_position, $contact_department, $contact_hospital, $contact_level, $path_profile);
            $error = $stmt->execute();
            if ($error) {
                echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
            } else {
                echo json_encode(array("result" => "Fail"));
            }
        } else {
            echo json_encode(array("result" => "ลำดับซ้ำไม่สามาถบันทึกได้"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'แอดมิน' && $method === 'delete_contact') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $contact_id = $data->contact_id;
    $sql = "DELETE FROM Contact WHERE contact_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $contact_id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการลบข้อมูลติดต่อเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
