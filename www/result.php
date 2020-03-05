<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header("Content-type: application/json; charset=UTF-8");

if ($role === 'patient' || $role === 'doctor' && $method === 'get_bodyresult1') {

}

if ($role === 'doctor' && $method === 'update_bodyresult1') {

}

if ($role === 'patient' || $role === 'doctor' && $method === 'get_bodyresult2') {

}

if ($role === 'doctor' && $method === 'update_bodyresult2') {

}

if ($role === 'patient' || $role === 'doctor' && $method === 'get_thyroidScan') {

}

if ($role === 'doctor' && $method === 'update_thyroidScan') {

}

if ($role === 'patient' || $role === 'doctor' && $method === 'get_thyroidUltra') {

}

if ($role === 'doctor' && $method === 'update_thyroidUltraPic') {

}

if ($role === 'doctor' && $method === 'update_thyroidUltraMass') {

}