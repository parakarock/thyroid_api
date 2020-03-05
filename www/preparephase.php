<?php
require './connect.php';

$method = $_GET['method'];
$role = $_GET['role'];
header("Content-type: application/json; charset=UTF-8");

if ($role === 'nurse' || $role === 'patient' && $method === 'get_preparephase') {

}

if ($role === 'nurse' && $method === 'insert_UTP') {

}

if ($role === 'nurse' && $method === 'update_preparephase') {

}

