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
        $author_desc = $saw->get('.excerpt p')->toArray();
        $name = $saw->get('.padLeft10 h1')->toArray();
        $author= $saw->get('.author a')->toArray();
        $publisher = $saw->get('.publisher a')->toArray();
        $publisher_time = $saw->get('.publisher i')->toArray();
        $otherInfor_i = $saw->get('.otherInfor i')->toArray();
        $otherInfor_em = $saw->get('.otherInfor em')->toArray();
        $sellPriceTit = $saw->get('.priceWrap .sellPrice')->toArray();
        $discount = $saw->get('.priceWrap .discount')->toArray();
        $old_price = $saw->get('.priceWrap .price')->toArray();




        $info['brief'] = trim($brief[0]['#text'][0]);  //内容简介
        $info['author_desc'] = trim($author_desc[0]['#text'][0]);  //作者简介

        $info['special']=trim($specialist[0]['p'][0]['#text'][0]);  //本书特色
        $info['title']=$name[0]['#text'][0];  //本书特色
        $info['author']=$author[0]['#text'][0];  //本书特色
        $info['publisher']=$publisher[0]['#text'][0];  //本书特色
        $info['publisher_time']=$publisher_time[0]['#text'][0];  //本书特色
        $info['page_kai']=$otherInfor_em[0]['#text'][0];  //本书特色
        $info['pages']=$otherInfor_i[0]['#text'][0];  //本书特色
        $info['price']=$sellPriceTit[0]['#text'][0];  //本书特色
        $info['discount']=$discount[0]['#text'][0];  //本书特色
        $info['oldprice']=$old_price[0]['#text'][0];  //本书特色

        echo json_encode($info);
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

