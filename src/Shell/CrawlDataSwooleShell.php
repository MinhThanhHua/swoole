<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Filesystem\Folder;
use Spatie\Crawler\Crawler;
use App\demo;
use App\getImage;

/**
 * CrawlDataBasic shell command.
 */
class CrawlDataSwooleShell extends Shell
{
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
        $Folder = new Folder(APP_DIR);

        include_once $Folder->path . '/Swoole.php';
        $this->Swoole = new \Swoole();
        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main()
    {
        $time = microtime(true);
        $page = PAGE;

        for ($i = 1; $i < $page; $i++) {
            $processes[$i] = $this->Swoole->swoole_process(function ($process) use ($i) {
                $url = "https://www.pexels.com/vi-vn/tim-kiem/v%E1%BA%BB%20%C4%91%E1%BA%B9p/?page=$i&seed=2019-10-03+07%3A53%3A04+%2B0000&type=";

                $html = file_get_html($url);

                $tag = $html->find(".js-photo-link.photo-item__link");
                $pages = [];
                for ($j = 0; $j < count($tag); $j++) {
                    $img = html_entity_decode($tag[$j]->find("img", 0)->src);
                    $pages[] = [
                        'img' => $img
                    ];
                    $imgMime = getimagesize($img)['mime'];
                    $type = explode('/', $imgMime)[1];
                    $fileName = pathinfo($img)['filename'];
                    $path = ROOT . '/tmp/dataSwooleShell/' . $fileName . '.' . $type;
                    file_put_contents($path, file_get_contents($img));
                }
            });
            $processes[$i]->start();
        }
        for ($i = 1; $i < $page; $i++) {
            \Swoole\Process::wait(true);
        }
        echo 'Time: ';
        var_dump (microtime(true) - $time);
    }
}
