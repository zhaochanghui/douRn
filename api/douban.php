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

                $info = $v['info'];
                $infoarr = explode('/', $info);

                $v['price'] = $infoarr[count($infoarr) - 1];
                $v['publish_time'] = $infoarr[count($infoarr) - 2];
                $v['publisher'] = $infoarr[count($infoarr) - 3];
                $v['author'] = $infoarr[count($infoarr) - 4];

                $v['li'] = $detailInfo['li'];
                $v['intro'] = $detailInfo['intro'];
                $v['author_intro'] = $detailInfo['author_intro'];

                $rs[$k] = $v;
            }
        }

        var_dump($rs);

        echo json_encode($rs);
        file_put_contents('./d.txt', json_encode($rs));

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

        $str = $ql->find('#info')->html();
        $arr = explode("<br>", $str);

        foreach ($arr as $key => $value) {
            $vv = str_replace('<br>', '', $value);
            $vv = strip_tags($vv);
            $vv = $this->trimall($vv);
            $arr[$key] = $vv;
        }

        unset($arr[11]);

        $intro = $ql->find('#link-report .intro')->html();
        $intro = strip_tags($intro);
        //$arr['intro'] = $intro;

        $author_intro = $ql->find('.intro:eq(1)')->text();
        // $arr['author_intro'] = $author_intro;

        return ['li' => $arr, 'intro' => $intro, 'author_intro' => $author_intro];
    }

    function match_chinese($chars, $encoding = 'utf8')
    {
        $pattern = ($encoding == 'utf8') ? '/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u' : '/[\x80-\xFF]/';
        preg_match_all($pattern, $chars, $result);
        $temp = join('', $result[0]);
        return $temp;
    }

    public function movile()
    {

        // 定义采集规则
        $rules = [
            // 采集文章标题
            'detailUrl' => ['.pic a', 'href'],
            // 采集文章作者
            'pic' => ['.pic img', 'src'],
            'title' => ['.pic img', 'alt'],
            'score' => ['.rating_num', 'text'],

            'pj' => ['.star', 'html'],

            // 采集文章内容
            // 'content' => ['.item', 'html'],
        ];
        $rt = QueryList::get($this->url)->rules($rules)->query()->getData();

        $arrpj = [];
        foreach ($rt as $k => $v) {
            $pos1 = strrpos($v['pj'], "<span>");
            $pos2 = strrpos($v['pj'], "</span>");

            $pj = substr($v['pj'], $pos1, $pos2);
            $pj = strip_tags($pj);
            // var_dump($k);
            // var_dump($pj);;
            $arrpj[$k] = $pj;
        }


        $m = [];
        foreach ($rt as $k => $v) {
            $v['pjs'] = $arrpj[$k];

            //导演，主演，时间等
            $detail = $this->movieDetail($v["detailUrl"]);
            //'intro'=>$intro,'actor'=>$actor,'showtime'=>$showtime,'label'=>$label,'info'=>$arr
            $v['intro'] = $detail['intro'];
            $v['actor'] = $detail['actor'];
            $v['showtime'] = $detail['showtime'];
            $v['label'] = $detail['label'];
            $v['info'] = $detail['info'];


            $m[$k] = $v;
        }

        file_put_contents('./movie.txt', json_encode($m));

        var_dump($m);
        die;
    }

    public function movieDetail($url)
    {
        $ql = QueryList::get($url);

        $rt = [];

        //        $rt['title'] = $ql->find('h1')->text();

        //主演，导演。等等
        $info = $ql->find('#info')->html();

        //内容简介
        $intro = $ql->find('.related-info .hidden')->html();
        $intro = strip_tags($intro);
        $intro = $this->trimall($intro);

        $arr = explode('<br>', $info);

        //演员
        $actor = $arr[2];
        $actor = strip_tags($actor);

        //上映时间
        $showtime = $arr[6];
        $showtime = strip_tags($showtime);

        //类型
        $label = $arr[3];
        $label = strip_tags($label);


        $rs = [
            'intro' => $intro, 'actor' => $actor, 'showtime' => $showtime, 'label' => $label, 'info' => $arr
        ];

        return $rs;
    }


    //音乐
    public function music()
    {

        // 定义采集规则
        $rules = [
            // 采集文章标题
            'detailUrl' => ['.pl2 a', 'href'],
            // 采集文章作者
           'info1' => ['.pl2>p', 'text'],
            'title' => ['.pl2 a', 'text','-span'],
            'rating_nums' => ['.rating_nums', 'text'],

            'pjs' => ['.star .pl', 'text'],

            // 采集文章内容
            // 'content' => ['.item', 'html'],
        ];
        $rt = QueryList::get($this->url)->rules($rules)->query()->getData();

        var_dump($rt);

    }

}

$obj = new Spider();

/*
图书
$obj->url = 'https://book.douban.com/top250?start=0';
$obj->getContent();
 */

/*
电影，名称，演员，时间，标签，年份

$obj->url = 'https://movie.douban.com/top250?start=0&filter=';
$obj->movile();
 */

/*
音乐
 */
$obj->url = 'https://music.douban.com/top250?start=0';
$obj->music();


