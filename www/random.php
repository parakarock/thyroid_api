<?php
function random_password($len)
{
    srand((double) microtime() * 10000000);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $numbers = "0123456789";
    $ret_str = "";
    $num = strlen($numbers);
    for ($i = 0; $i < $len; $i++) {
        $ret_str .= $numbers[rand() % $num];
        $ret_str .= "";
    }
    return $ret_str;
}