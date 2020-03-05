<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header("Content-type: application/json; charset=UTF-8");

//ขอ role เรียก get_role
if ($method === 'get_role') {
    $postdata = file_get_contents("php://input");
    $idCard = json_decode($postdata)->idcard;
    $sql = "SELECT role_name FROM Role WHERE person_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
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

//ขอข้อมูล profile เรียก get_profile ส่ง role
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_profile') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM User_profile WHERE person_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $idCard);
    $stmt->execute();
    $result1 = $stmt->get_result();

    $sql = "SELECT * FROM Hospital_send WHERE log_data_person_id = ? AND log_data_rou_id = ?";
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
/*-------------------------------------------------------------------------------------------------------------------*/
// ***health data***

//ข้อมูลตาราง init_symp เรียก get_init สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_init') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM init_symp WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

//ข้อมูลตาราง symp_phase เรียก get_symp สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_symp') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM symp_phase WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

//ข้อมูลตาราง thyroid_mineral_therapy เรียก get_mineral_therapy สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_mineral_therapy') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM thyroid_mineral_therapy WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

//ข้อมูลตาราง mineral_history เรียก get_mineral_history สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_mineral_history') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM mineral_history WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

/*-------------------------------------------------------------------------------------------------------------------*/
// ***ผลการตรวจทางห้องปฎิบัติการ***
//ข้อมูลตาราง Lab_test เรียก get_Lab_test สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_Lab_test') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Lab_test WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

/*-------------------------------------------------------------------------------------------------------------------*/
// ***ขั้นตอนการเตรียมตัว***
//ข้อมูลตาราง prepare_phase เรียก get_prepare_phase สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_prepare_phase') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM prepare_phase WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

/*-------------------------------------------------------------------------------------------------------------------*/
// ***ผลการตรวจ***

//ผลการตรวจร่างกาย
//ข้อมูลตาราง body_result เรียก get_body_result สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_body_result') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM prepare_phase WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row);
        }
        echo json_encode($resultArray);
    } else {
        echo json_encode(array("result" => "Fail"));
    }
}

//ผลการทางห้องปฎิบัติการ
//ข้อมูลตาราง Lab_test เรียก get_Lab_test สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล

//ผลการตรวจรังสีวินิจฉัย
//Thyroid scan

//Thyroid ultrasound
//ข้อมูล รูป + รายละเอียดแต่ละก้อน เรียก get_ultrasound สิทธิ์เรียกดู หมอ,ผู้ป่วย,พยาบาล
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_ultrasound') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT thyroid_image FROM thyroid_ultrasound WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result1 = $stmt->get_result();

    $sql = "SELECT thy_num,thy_size,thy_desc,thy_advise,thy_ult_round,surgury_send,thy_ult_date  FROM thyroid_ultrasound AS d1
             INNER JOIN thyroid_img  AS d2
             ON  (d1.thy_img_id = d2.img_id)
              INNER JOIN thyroid_ult_detail  AS d3
             ON  (d2.ult_id = d3.thy_ult_id) WHERE d1.log_data_person_id = ? AND d1.log_data_rou_id = ?";
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
/*-------------------------------------------------------------------------------------------------------------------*/
//ผลการเจาะตรวจชิ้นเนื้อ และ ผลวินิจฉัย



/*-------------------------------------------------------------------------------------------------------------------*/
//ผลการกลืนแร่ไอโอดีน
if ($role === 'nurse' || $role === 'patian' || $role === 'docter' && $method === 'get_result_iodine') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT iodine_amount,treatment_result FROM iodine_result WHERE log_data_person_id = ? AND log_data_rou_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $stmt->execute();
    $result1 = $stmt->get_result();

    $sql = "SELECT thyroid_size,TT3_fT3_result,fT4_result,TSH_result,iod_res_anti_name,iod_res_anti_amount,iod_res_anti_daily,iod_res_beta_name,iod_res_beta_amount,iod_res_beta_daily  FROM iodine_result AS d1
             INNER JOIN iodine_result_detail_has_iodine_result  AS d2
             ON  (d1.iod_res_id = d2.iod_res_id)
              INNER JOIN iodine_result_detail  AS d3
             ON  (d2.iod_res_de_id = d3.iod_res_de_id ) WHERE d1.log_data_person_id = ? AND d1.log_data_rou_id = ?";
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

/*-------------------------------------------------------------------------------------------------------------------*/
//ติดตามผลการรักษา