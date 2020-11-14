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
            echo json_encode(array("result" => "รหัสบัตรประชาชนหรือรหัสผ่านไม่ถูกต้อง"));
        }
    } else {
        echo json_encode(array("result" => "รหัสบัตรประชาชนหรือรหัสผ่านไม่ถูกต้อง"));
    }

}


if (($role === 'ผู้ป่วย' || $role === 'หมอ'|| $role === 'พยาบาล'|| $role === 'แอดมิน')&& $method === 'changePassTab') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $username = $data->idcard;
    $oldpass = $data->oldpass;
    $newpass = $data->newpass;
    $sql = "SELECT * FROM Authen WHERE username = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',  $username);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if($row['password'] === $oldpass){
            $sql = "UPDATE Authen SET password=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss',$newpass ,$username );
            $error = $stmt->execute();
            if ($error) {
                echo json_encode(array("username" => $username, "password" => $newpass));
            } else {
                echo json_encode(array("result" => "Fail"));
            }
        }else{
            echo json_encode(array("result" => "รหัสผ่านเดิมไม่ตรงกัน"));
        }
        
    } else {
        echo json_encode(array("result" => "ไม่พบข้อมูลผู้ใช้งาน"));
    }

}

if ($role === 'guest' && $method === 'changePassLogin1') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $person_id = $data->person_id;
    $birthdate = $data->birthdate;
    $sql = "SELECT * FROM User_profile WHERE person_id = ? AND birthdate = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss',  $person_id,$birthdate);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array("result" => "Ok"));
    } else {
        echo json_encode(array("result" => "ไม่พบข้อมูลผู้ใช้งาน"));
    }

}

if (($role === 'guest' )&& $method === 'changePassLogin2') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $person_id = $data->person_id;
    $newpass = $data->newpass;
    $sql = "UPDATE Authen SET password=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss',$newpass ,$person_id );
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("username" => $person_id, "password" => $newpass));
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if (($role === 'ผู้ป่วย' )&& $method === 'getRounds') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $person_id = $data->person_id;
    $sql = "SELECT rou_id FROM Patient_Summary WHERE person_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$person_id);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($error) {
        if ($result->num_rows > 0) {
            $count = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                array_push($resultArray, $row);
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode(array("result" => "Invalid Data of `$idCard`"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}



