<?php
header("Access-Control-Allow-Origin: *");
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
            'title' => ['.pl2>a', 'text'],
            'img' => ['.nbg>img', 'src'],
            'detail' => ['.nbg', 'href'],
            'info' => ['p.pl', 'text'],
            'pj' => ['span.pl', 'text'],

        ];

        $rt = QueryList::get($this->url)->rules($rules)->query()->getData();
        $rs = [];
        foreach ($rt as $k => $v) {
            if (isset($v['title'])) {
                $title = $v['title'];
                $title = $this->trimall($title);
                $v['title'] = $title;

                $pj = $v['pj'];
                $pj = str_replace('(', '', $pj);
                $pj = str_replace(')', '', $pj);
                $pj = $this->trimall($pj);

                $v['pj'] = $pj;
//                echo $pj."\n";

                $detailInfo = $this->getDetail($v['detail']);

                $v['key'] = $k + 1;
                $rs[$k] = $v;
            }
        }


        var_dump($rs);

//        echo json_encode($rs);
//        file_put_contents('./d.txt',json_encode($rs));


    }

    public function trimall($str)
    {
        $oldchar = array(" ", "　", "\t", "\n", "\r");
        $newchar = array("", "", "", "", "");
        return str_replace($oldchar, $newchar, $str);
    }


    public function getDetail($url)
    {




        $ql = QueryList::get($url);

        $rt = [];

        $name = $ql->find('#info>.pl')->text();
        $all = $ql->find('#info')->text();

        $name = explode(":",$name);

        foreach ($name as $k=>$v){

            if($k!=count($name)-1) {
                $tmp = $name[$k + 1];
                $tmp = $this->trimall($tmp);
                $pos1 = strpos($all, $tmp);
                var_dump($pos1);

            }

        }





        var_dump(strpos($all,'出版社'));
        die;

    }


    function match_chinese($chars, $encoding = 'utf8')
    {
        $pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u' : '/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);
        return $temp;
    }


}


$obj = new Spider();
$obj->url = 'https://book.douban.com/top250?start=0';
$obj->getContent();