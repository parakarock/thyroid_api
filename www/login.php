<?php

require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-type: application/json; charset=UTF-8');

if ($role === 'guest' && $method === 'login') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $username = $data->username;
    $password = $data->password;
    $sql = "SELECT * FROM Authen WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss',  $username,$password);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {

        $sql = "SELECT * FROM User_profile WHERE person_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',  $username);
        $error = $stmt->execute();
        $result1 = $stmt->get_result();

        $sql = "SELECT * FROM Role WHERE person_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',  $username);
        $error = $stmt->execute();
        $result2 = $stmt->get_result();

        $resultArray1 = array();
        $resultArray2 = array();

        if ($result1->num_rows > 0 && $result2->num_rows > 0 ) {
            $row1 = $result1->fetch_assoc();
            array_push($resultArray1, $row1);
             while ($row2 = $result2->fetch_assoc()) {
                array_push($resultArray2, $row2);
            }
            array_push($resultArray1,$resultArray2);
            echo json_encode($resultArray1);
        } else {
            echo json_encode(array("result" => "Invalid Data of ` $username`"));
        }
    } else {
        echo json_encode(array("result" => "Your username or password is wrong!"));
    }

}
