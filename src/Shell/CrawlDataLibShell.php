<?php

namespace App\Shell;

use Cake\Console\Shell;
use phpDocumentor\Reflection\Types\Object_;
use Spatie\Crawler\Crawler;
use App\demo;
use App\getImage;

/**
 * CrawlDataShell shell command.
 */
class CrawlDataLibShell extends Shell
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
        return $parser;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     * Trường hợp chỉ dùng thư viện, không đa luồng
     */
    public function main()
    {
        $page = PAGE;
        $time = microtime(true);
        for ($i = 1; $i < $page; $i++) {
            $url = "https://www.pexels.com/vi-vn/tim-kiem/v%E1%BA%BB%20%C4%91%E1%BA%B9p/?page=$i&seed=2019-10-03+07%3A53%3A04+%2B0000&type=";
            Crawler::create()
                ->ignoreRobots()
                ->setMaximumCrawlCount(1)
                ->addCrawlObserver(new getImage('dataLib'))
                ->startCrawling($url);
        }
        echo 'Time: ';
        var_dump(microtime(true) - $time);
    }
}
