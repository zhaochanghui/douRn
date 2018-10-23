<?php

header("Access-Control-Allow-Origin: *");

include 'nokogiri.php';
// $json = json_decode(file_get_contents('php://input'), true);

// $arr =[$json['name'],$json['pwd']];

// echo json_encode($arr);
//
//

class Api
{
    public static $movies = [];

    public function run($num)
    {
        for ($i = 1; $i < $num; $i++) {

            echo $i . "\n";
            $url = 'http://www.langfly.com/interactive/person/0&now=' . $i;
            $this->add($url);
        }

        file_put_contents('./m.txt', json_encode(self::$movies));
    }

    //抓取
    public function add($url)
    {
        $html = file_get_contents($url);

        $saw = new nokogiri($html);

        $src_title = $saw->get('.dianying-liebiao-2 img')->toArray();
        $content = $saw->get('.dianying-liebiao-right-2')->toArray();

        foreach ($src_title as $key => $value) {
            $arr1 = ['title' => $value['alt'], 'src' => $value['src'], 'content' => $content[$key]['#text'][0], 'time' => date('Y-m-d H:i:s')];
            array_push(self::$movies, $arr1);
            // echo "标题：".$value['alt'].'   src:'.$value['src']."  content:".$content[$key]['#text'][0]."<br>";
        }
    }

    public function getMovies()
    {
        return file_get_contents("./m.txt");
    }
}

 //$api = new Api();
// $api->run(8);

// $data = $api->getMovies();
// var_dump($data);

// file_put_contents('./m.txt', json_encode($arr));
// echo json_encode($arr);

$str = file_get_contents('./m.txt');

//echo $str;


$newarr = json_decode($str,1);
foreach($newarr as $k=>$v){
	$newarr[$k]['key']=$k+1;
}


$str1 = json_encode($newarr);
echo $str1;


//$arr = json_decode($str, true);
#
#$i = 0;
#$arrr = [];
#foreach ($arr as $key => $value) {
#    array_push($arrr, $value);
#    $i++;
#
#    if ($i == 30) {
#        break;
#  //  }
#//}
#
#//file_put_contents('./m30.txt', json_encode($arrr));

