<?php
include 'nokogiri.php';
include './vendor/autoload.php';

use QL\QueryList;


class Spider
{
    public $url;

    public function __construct()
    {
    }


    //豆瓣图书top250
    public function getContent()
    {


//        $ql = QueryList::get('https://book.douban.com/top250?start=0');
//        $rt[] = $ql->find('img')->attr('src');
//
        echo '<pre>';
        $rules = [
            // 采集文章标题
            'title' => ['.pl2>a','text'],
            'img' => ['.nbg>img','src'],
            'url'=>['.nbg','href'],
            'info'=>['p.pl','text'],
            'pingjia'=>['span.pl','text'],

        ];

        $rt = QueryList::get($this->url)->rules($rules)->query()->getData();
        var_dump($rt->all());


    }
}


$obj = new Spider();
$obj->url = 'https://book.douban.com/top250?start=0';
$obj->getContent();