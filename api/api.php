<?php

header("Access-Control-Allow-Origin: *");

include 'nokogiri.php';


$a = isset($_GET['a'])?$_GET['a']:false;

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

    $one = [];
    foreach ($newarr as $k => $v) {
        $one = $v;
        $one['key'] = $k + 1;
        break;
//        $newarr[$k]['key'] = $k + 1;
    }


    $str1 = json_encode($one);
    echo $str1;
}


