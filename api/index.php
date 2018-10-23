<?php
header("Access-Control-Allow-Origin: *");

include './nokogiri.php';

class Api
{
    public $url='';
    public $prefix='http://www.bookschina.com';

    public function __construct()
    {
        $this->url='http://www.bookschina.com/24hour/1_0_';
    }

    //抓取
    public function scray()
    {
        $html = file_get_contents($this->url);
        $html = iconv("gb2312","utf-8//IGNORE",$html);
        $saw = new nokogiri($html);

        $rs = [];
        $a_info = $saw->get('.bookList .cover a')->toArray();
        $name = $saw->get('.infor .name a')->toArray();
        $author = $saw->get('.infor .author a')->toArray();
        $publisher = $saw->get('.infor .publisher a')->toArray();
        $price = $saw->get('.infor .priceWrap .sellPrice')->toArray();

        foreach ($a_info as $k=>$v){
            $rs[$k]['href'] = $this->prefix.$v['href']; //详情页链接
            $rs[$k]['title'] = $name[$k]['#text'][0];  //书名
            $rs[$k]['img'] = $v['img'][0]['data-original'];  //作者
            $rs[$k]['publisher'] = $publisher[$k]['#text'][0];  //作者
            $rs[$k]['price'] = $price[$k]['#text'][0];  //作者
            $rs[$k]['key'] = $k+1;  //作者

        }

        echo json_encode($rs);

    }


    //抓取详情
    public function getDetail($url)
    {
        $html = file_get_contents($url);
        $html = iconv("gb2312","utf-8//IGNORE",$html);
        $saw = new nokogiri($html);

        $info = [];
        $specialist = $saw->get('.specialist')->toArray();
        $brief = $saw->get('.brief p')->toArray();
        $excerpt = $saw->get('.excerpt p')->toArray();


        $info['brief'] = trim($brief[0]['#text'][0]);  //内容简介
        $info['excerpt'] = trim($excerpt[0]['#text'][0]);  //作者简介

        $info['special']=trim($specialist[0]['p'][0]['#text'][0]);  //本书特色

        var_dump($info);
    }

}


$href = isset($_GET['a'])?$_GET['a']:'';
$obj = new Api();

if($href==''){
    $obj->scray();
}else{
    //http://localhost:9999/?a=http://www.bookschina.com/5731526.htm
    $obj->getDetail($href);
}

