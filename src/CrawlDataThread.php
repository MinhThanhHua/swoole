<?php

include dirname(__DIR__) . '/src/simple_html_dom.php';
include dirname(__DIR__) . '/config/constant.php';

class CrawlDataThread extends Thread
{
    protected $i;

    public function __construct($i)
    {
        $this->i = $i;
    }

    public function run()
    {
        $url = "https://www.pexels.com/vi-vn/tim-kiem/v%E1%BA%BB%20%C4%91%E1%BA%B9p/?page=$this->i&seed=2019-10-03+07%3A53%3A04+%2B0000&type=";

        $html = file_get_html($url);

        $tag = $html->find(".js-photo-link.photo-item__link");
        $pages = [];

        for ($i = 0; $i < count($tag); $i++) {
            $img = html_entity_decode($tag[$i]->find("img", 0)->src);
            $pages[] = [
                'img' => $img
            ];
            $imgMime = getimagesize($img)['mime'];
            $type = explode('/', $imgMime)[1];
            $fileName = pathinfo($img)['filename'];
            file_put_contents(dirname(__DIR__) . '/tmp/dataThread/image' . $fileName . '.' . $type,
                file_get_contents($img));
        }
    }
}

$page = PAGE;
$time = microtime(true);
$pool = new Pool($page);

for ($i = 1; $i < $page; $i++) {
    $pool->submit(new CrawlDataThread($i));
}

while ($pool->collect()) {
    ;
}

$pool->shutdown();
echo('Time: ');
echo(microtime(true) - $time);
