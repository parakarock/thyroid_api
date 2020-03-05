<?php
require './connect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$idCard = $request->idcard;
$title = $request->title;
$firstname = $request->firstname;
$lastname = $request->lastname;
$dof = $request->dof;
$gender = $request->gender;
$national = $request->national;
$status = $request->status;
$phone = $request->phone;
// prepare and bind
$sql = "INSERT INTO User_profile (person_id,title,firstname,lastname,birthdate,gender,nationality,status,phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssssss', $idCard, $title, $firstname, $lastname, $dof, $gender, $national, $status, $phone);

// // set parameters and execute



$stmt->execute();


echo "{$idcard} records created successfully";

$stmt->close();
$conn->close();
