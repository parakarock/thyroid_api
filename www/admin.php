<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if ($role === 'แอดมิน' && $method === 'insert_hospital') {
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

    $sql = "SELECT * FROM Hospital WHERE hos_name = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $hos_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(array("result" => "hospital name already exist."));
    } else {
        if ($hos_name) {
            $sql = "INSERT INTO Hospital (id, h_id, hos_name, address, phone, freeT3_standard,
                freeT4_standard, TSH_standard, TRAb_standard) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssdddd', $h_id, $hos_name, $address, $phone, $freeT3_standard, $freeT4_standard, $TSH_standard, $TRAb_standard);
            $error = false;
            $error = $stmt->execute();
            if ($error) {
                echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
            } else {
                echo json_encode(array("result" => "Fail"));
            }
        }

    }

}

if ($role === 'แอดมิน' && $method === 'update_hospital') {
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
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'แอดมิน' && $method === 'delete_hospital') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $id = $data->id;
    $sql = "DELETE FROM Hospital WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการลบข้อมูลโรงพยาบาลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if (($role === 'พยาบาล' || $role === 'แอดมิน') && $method === 'get_hospital') {
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

if ($role === 'แอดมิน' && $method === 'insert_user') {
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
            if ($patient == 'add') {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'ผู้ป่วย', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();

                if ($error) {
                    $sql = "INSERT INTO Patient_Summary (person_id,rou_id,volume_result,iodine_result) VALUES (?,'1',NULL,NULL)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $idCard);
                    $error = $stmt->execute();
                } else {
                    echo json_encode(array("result" => "Fail"));
                }

            }
            if ($doctor == 'add') {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'หมอ', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {

                } else {
                    echo json_encode(array("result" => "Fail"));
                }
            }
            if ($nurse == 'add') {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'พยาบาล', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {

                } else {
                    echo json_encode(array("result" => "Fail"));
                }
            }
            $sql = "INSERT INTO Authen (username,password) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $idCard, $idCard);
            $error = $stmt->execute();
            if ($error) {
                echo json_encode(array("username" => $idCard, "password" => $idCard));
            } else {
                echo json_encode(array("result" => "Fail"));
            }

        } else {
            echo json_encode(array("result" => "Fail"));
        }
    }
}

if ($role === 'แอดมิน' && $method === 'get_user') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $search = $data->search;
    $sql = "SELECT CONCAT(b.title,b.firstname,' ',b.lastname) as name,
            b.person_id,
            b.title,
            b.firstname,
            b.lastname,
            b.birthdate,
            b.gender,
            b.nationality,
            b.status,
            b.phone
            FROM Role as a
            INNER JOIN User_profile as b
            ON a.person_id = b.person_id
            WHERE a.role_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $search);
    $stmt->execute();
    $result = $stmt->get_result();

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

if ($role === 'แอดมิน' && $method === 'update_user') {
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

    $patient = $data->patient;
    $doctor = $data->doctor;
    $nurse = $data->nurse;

    $error = false;
    $sql = "UPDATE User_profile SET title=?,firstname=?,lastname=?,birthdate=?,gender=?,nationality=?,status=?,phone=? WHERE person_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssss', $title, $firstname, $lastname, $dof, $gender, $national, $status, $phone, $idCard);
    $error = $stmt->execute();
    if ($error) {

        if ($patient == null) {
            $sql = "DELETE FROM Role WHERE person_id = ? AND role_name = 'ผู้ป่วย'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $error = $stmt->execute();
            if ($error) {
                $sql = "DELETE FROM Patient_Summary WHERE person_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
            } else {
                echo json_encode(array("result" => "Fail"));
            }
        }
        if ($patient == "add") {
            $sql = "SELECT * FROM Role WHERE person_id = ? AND role_name = 'ผู้ป่วย'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {

            } else {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'ผู้ป่วย', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {
                    $sql = "INSERT INTO Patient_Summary (person_id,rou_id) VALUES (?,'1')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $idCard);
                    $error = $stmt->execute();
                } else {
                    echo json_encode(array("result" => "Fail"));
                }
            }

        }

        if ($doctor == null) {
            $sql = "DELETE FROM Role WHERE person_id = ? AND role_name = 'หมอ'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $error = $stmt->execute();
            if ($error) {

            } else {
                echo json_encode(array("result" => "Fail"));
            }
        }
        if ($doctor == "add") {
            $sql = "SELECT * FROM Role WHERE person_id = ? AND role_name = 'หมอ'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {

            } else {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'หมอ', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {

                } else {
                    echo json_encode(array("result" => "Fail"));
                }
            }

        }
        if ($nurse == null) {
            $sql = "DELETE FROM Role WHERE person_id = ? AND role_name = 'พยาบาล'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $error = $stmt->execute();
            if ($error) {

            } else {
                echo json_encode(array("result" => "Fail"));
            }
        }
        if ($nurse == "add") {
            $sql = "SELECT * FROM Role WHERE person_id = ? AND role_name = 'พยาบาล'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $idCard);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {

            } else {
                $sql = "INSERT INTO Role (id,role_name,person_id) VALUES (NULL,'พยาบาล', ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $idCard);
                $error = $stmt->execute();
                if ($error) {

                } else {
                    echo json_encode(array("result" => "Fail"));
                }
            }

        }

        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'แอดมิน' && $method === 'delete_user') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $sql = "DELETE FROM User_profile WHERE person_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการลบข้อมูลผู้ใช้เสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

if ($role === 'แอดมิน' && $method === 'getrole_user') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $sql = "SELECT * FROM Role WHERE person_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($result->num_rows > 0 ) {
         while ($row = $result->fetch_assoc()) {
                array_push($resultArray, $row);
            }
         echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}
