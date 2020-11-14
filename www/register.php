<?php
require './connect.php';
require './random.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if ($role === 'พยาบาล' && $method === 'insert_pantient') {
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
    $Hos_base_h_id = $data->Hos_base_h_id;
    $from_name = $data->from_name;
    $to_name = $data->to_name;
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
            $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'ผู้ป่วย', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $error = $stmt->execute();
            if ($error) {
                $sql = "INSERT INTO Patient_Summary (person_id,rou_id) VALUES (?,'1')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {
                    $sql = "UPDATE Hospital_send SET from_h_id = ?, to_h_id =?, Hos_base_h_id=? , from_hn = ?, to_hn = ?, Hos_base_hn = 'โรงพยาบาลมหาวิทยาลัยบูรพา' WHERE person_id = ? AND rou_id ='1' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssss', $from_id , $to_id ,$Hos_base_h_id, $from_name , $to_name , $idCard );
                    $error = $stmt->execute();
                    if ($error) {
                        $sql = "INSERT INTO Authen (username,password) VALUES (?,?)";
                        $stmt = $conn->prepare($sql);
                        $passw = random_password(6);
                        $stmt->bind_param('ss', $idCard, $idCard);
                        $error = $stmt->execute();
                        if ($error) {
                            $row = $result->fetch_assoc();
                            $sql = "SELECT person_id,concat(title,firstname,' ',lastname) as fullname ,gender FROM User_profile WHERE person_id = ? ";
    	                    $stmt = $conn->prepare($sql);
    	                    $stmt->bind_param('s', $idCard);
    	                    $stmt->execute();
    	                    $result1 = $stmt->get_result();

		                    $sql = "SELECT rou_id FROM Patient_Summary WHERE person_id = ?";
    	                    $stmt = $conn->prepare($sql);
    	                    $stmt->bind_param('s', $idCard);
    	                    $stmt->execute();
    	                    $result2 = $stmt->get_result();
    	                    $resultArray1 = array();
		                    $resultArray2 = array();
                        if ($result1->num_rows > 0 && $result2->num_rows > 0) {
                            while ($row = $result1->fetch_assoc()) {
                                array_push($resultArray1, $row);
                            }
                            while ($row = $result2->fetch_assoc()) {
                                array_push($resultArray2, $row);
                            }
		                        array_push($resultArray1, $resultArray2);
                                array_push($resultArray1, array("username" => $idCard, "password" => $idCard));
                                echo json_encode($resultArray1);
                        } else {
                            echo json_encode(array("result" => "ไม่พบข้อมูล"));
                        }
                        } else {
                            echo json_encode(array("result" => "Fail5"));
                        }
                    } else {
                        echo json_encode(array("result" => "Fail4"));
                    }

                } else {
                    echo json_encode(array("result" => "Fail3"));
                }
            } else {
                echo json_encode(array("result" => "Fail2"));
            }
        } else {
            echo json_encode(array("result" => "Fail1"));
        }
    }
}



