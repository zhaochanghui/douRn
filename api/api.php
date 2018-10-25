<?php

header("Access-Control-Allow-Origin: *");

include 'nokogiri.php';


$a = isset($_GET['key'])?$_GET['key']:false;

$str = file_get_contents('./d.txt');

$newarr = json_decode($str,1);

if(!$a) {
    foreach ($newarr as $k => $v) {
        $newarr[$k]['key'] = $k + 1;
    }


    $str1 = json_encode($newarr);
    echo $str1;
}

if($a){

    $index = $a-1;
    $one = $newarr[$index];

    $str1 = json_encode($one);
    echo $str1;
}


