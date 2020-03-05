<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header("Content-type: application/json; charset=UTF-8");

if (($role === 'nurse' || $role === 'admin') && $method === 'insert_profile') {
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
    $sql = "INSERT INTO User_profile (person_id,title,firstname,lastname,birthdate,gender,nationality,status,phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssss', $idCard, $title, $firstname, $lastname, $dof, $gender, $national, $status, $phone);
    $error = $stmt->execute();
	if ($error) {
        echo json_encode(array("result" => "OK"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
    

}

if ($role === 'nurse' && $method === 'update_labtest') {

}

if ($role === 'nurse' && $method === 'insert_labtest') {

}
