<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Content-type: application/json; charset=UTF-8");

if (($role === 'ผู้ป่วย' || $role === 'หมอ'|| $role === 'พยาบาล') && $method === 'get_labtest') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $sql = "SELECT * FROM Lab_test WHERE person_id = ? AND rou_id = ? ORDER BY id_test DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $idCard, $round);
    $error = $stmt->execute();
    $result = $stmt->get_result();
    $resultArray = array();
    if ($error) {
        if ($result->num_rows > 0) {
            $count = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                $row["index"] = $count;
                array_push($resultArray, $row);
                $count--;
            }
            echo json_encode($resultArray);
        } else {
            echo json_encode(array("result" => "ไม่พบข้อมูล `$idCard`"));
        }
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'พยาบาล' && $method === 'update_labtest') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $id_test = $data->id_test;
    $t3 = $data->t3;
    $hospital = $data->hospital;
    $t4 = $data->t4;
    $tsh = $data->tsh;
    $trab = $data->trab;
    $t3stan = $data->t3stan;
    $t4stan = $data->t4stan;
    $tshstan = $data->tshstan;
    $trabstan = $data->trabstan;
    $antiname = $data->antiname;
    $betaname = $data->betaname;
    $antiamount = $data->antiamount;
    $betaamount = $data->betaamount;
    $labdate = $data->labdate;
    $antidaily = $data->antidaily;
    $betadaily = $data->betadaily;
    $exec = false;
    $sql = "UPDATE Lab_test SET  free_T3=?, Lab_test_hosp=?, free_T4=?, TSH=?, TRAb=?,
    free_T3_stan=?, free_T4_stan=?, TSH_stan=?, TRAb_stan=?, anti_thy_name=?,beta_thy_name=?, anit_thyroid_amount=?, beta_block_amount=?,
     lab_date=?,anit_thyroid_daily=?, beta_block_daily=?  WHERE id_test=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('dsdddddddssddsiii', $t3, $hospital, $t4, $tsh, $trab, $t3stan, $t4stan, $tshstan, $trabstan, $antiname, $betaname, $antiamount, $betaamount, $labdate, $antidaily, $betadaily,$id_test);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}

if ($role === 'พยาบาล' && $method === 'insert_labtest') {
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata);
    $idCard = $data->idcard;
    $round = $data->round;
    $t3 = $data->t3;
    $hospital = $data->hospital;
    $t4 = $data->t4;
    $tsh = $data->tsh;
    $trab = $data->trab;
    $t3stan = $data->t3stan;
    $t4stan = $data->t4stan;
    $tshstan = $data->tshstan;
    $trabstan = $data->trabstan;
    $antiname = $data->antiname;
    $betaname = $data->betaname;
    $antiamount = $data->antiamount;
    $betaamount = $data->betaamount;
    $labdate = $data->labdate;
    $antidaily = $data->antidaily;
    $betadaily = $data->betadaily;
    $exec = false;
    $sql = "INSERT INTO Lab_test (id_test, person_id, rou_id, free_T3, Lab_test_hosp, free_T4, TSH, TRAb,
    free_T3_stan, free_T4_stan, TSH_stan, TRAb_stan, anti_thy_name,beta_thy_name, anit_thyroid_amount, beta_block_amount,
     lab_date,anit_thyroid_daily, beta_block_daily) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sidsdddddddssddsii', $idCard, $round, $t3, $hospital, $t4, $tsh, $trab, $t3stan, $t4stan, $tshstan, $trabstan, $antiname, $betaname, $antiamount, $betaamount, $labdate, $antidaily, $betadaily);
    $error = $stmt->execute();
    if ($error) {
        echo json_encode(array("result" => "ดำเนินการบันทึกข้อมูลเสร็จสิ้น"));
    } else {
        echo json_encode(array("result" => "Fail"));
    }

}
