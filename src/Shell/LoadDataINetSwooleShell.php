<?php

namespace App\Shell;

use App\Model\Table\News1Table;
use App\Model\Table\NewsTable;
use Cake\Console\Shell;
use Spatie\Crawler\Crawler;
use App\getTitle;
use Cake\Filesystem\Folder;

/**
 * LoadDataINetSwoole shell command.
 *
 * @property News1Table $News1
 * @property NewsTable $News
 * @property \Swoole $Swoole
 */
class LoadDataINetSwooleShell extends Shell
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
        $this->loadModel('News1');
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
        $data = [];
        $saveData = [];
        for ($i = 1; $i < PAGE_VOZ; $i++) {
            $processes[$i] = $this->Swoole->swoole_process(function ($process) use ($i) {
                $url = sprintf(URL, $i);
                $html = file_get_html($url);

                $tag = $html->find("h3[class=threadtitle]");
                for ($j = 0; $j < count($tag); $j++) {
                    $text = $tag[$j]->find('a', 0)->plaintext;
                    $data[] = $text;
                }
                $str = json_encode($data);
                $process->write($str);
            });
            if ($i % 4 == 0) {
                \Swoole\Process::wait(true);
                \Swoole\Process::wait(true);
                \Swoole\Process::wait(true);
                \Swoole\Process::wait(true);
                $processes[$i]->start();
            } else {
                $processes[$i]->start();
            }
        }
        for ($i = 1; $i < PAGE_VOZ; $i++) {
            $data[] = json_decode($processes[$i]->read(), true);
            \Swoole\Process::wait(true);
        }

        foreach ($data as $key => $item) {
            foreach ($item as $value) {
                array_push($saveData, [
                    'title' => $value
                ]);
            }
        }

        if (count($saveData) > 0) {
            $newData = $this->News1->newEntities($saveData);
            $this->News1->saveMany($newData);
            echo 'okay';
        }
    }
}
