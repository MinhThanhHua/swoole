<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Filesystem\Folder;

include APP_DIR . '/simple_html_dom.php';

use DOMDocument;
use DomXPath;

/**
 * CrawlDataThreadBasic shell command.
 *
 * @property ThreadBasic ThreadBasic
 */
class CrawlDataShell extends Shell
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Manage the available sub-commands along with their arguments and help
     *
     * @see http://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        return $parser;
    }


    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     * Trường hợp crawl data không dùng thư viện + không dùng đa luồng
     */
    public function main()
    {
        $time = microtime(true);
        $page = PAGE;
        for ($i = 1; $i < $page; $i++) {

            $url = "https://www.pexels.com/vi-vn/tim-kiem/v%E1%BA%BB%20%C4%91%E1%BA%B9p/?page=$i&seed=2019-10-03+07%3A53%3A04+%2B0000&type=";

            $html = file_get_html($url);

            $tag = $html->find(".js-photo-link.photo-item__link");
            $pages = [];

            for ($j = 0; $j < count($tag); $j++) {
                // Xóa những ký tự đặc biệt của html
                $img = html_entity_decode($tag[$j]->find("img", 0)->src);
                $pages[] = [
                    'img' => $img
                ];
                $imgMime = getimagesize($img)['mime'];
                $type = explode('/', $imgMime)[1];
                $fileName = pathinfo($img)['filename'];
                $path = ROOT . '/tmp/data/' . $fileName . '.' . $type;
                file_put_contents($path, file_get_contents($img));
            }
        }
        echo 'Time: ';
        var_dump(microtime(true) - $time);
    }
}


