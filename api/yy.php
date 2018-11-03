<?php

header("Access-Control-Allow-Origin: *");

include 'nokogiri.php';

 function trimall($str)
{
    $oldchar = array(" ", "　", "\t", "\n", "\r");
    $newchar = array("", "", "", "", "");
    return str_replace($oldchar, $newchar, $str);
}



$a = isset($_GET['key'])?$_GET['key']:false;

$str = file_get_contents('./music.txt');

$newarr = json_decode($str,1);

if(!$a) {
    foreach ($newarr as $k => $v) {
        foreach ($v as $vk=>$vv){
            $vv = trimall($vv);
            $newarr[$k][$vk] = $vv;
        }

        $newarr[$k]['key'] = $k + 1;
    }


    $str1 = json_encode($newarr);
    echo $str1;
}

if($a){

    $index = $a-1;
    $one = $newarr[$index];

    $pjs = $one['pjs'];
    $pjs = str_replace('(','',$pjs);
    $pjs = str_replace(')','',$pjs);
    $pjs = trimall($pjs);
    $one['pjs'] = $pjs;

    $str1 = json_encode($one);
    echo $str1;
}


