<?php

namespace App\Shell;

use App\Model\Table\NewsTable;
use Cake\Console\Shell;
use Spatie\Crawler\Crawler;
use App\getTitle;

/**
 * LoadDataINet shell command.
 *
 * @property NewsTable $News
 */
class LoadDataINetShell extends Shell
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('News');
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
     */
    public function main()
    {
        $data = [];
        for ($i = 1; $i < PAGE_VOZ; $i++) {
            $url = sprintf(URL, $i);
            $html = file_get_html($url);

            $tag = $html->find("h3[class=threadtitle]");
            for ($j = 0; $j < count($tag); $j++) {
                $text = $tag[$j]->find('a', 0)->plaintext;
                array_push($data, [
                    'title' => $text
                ]);
            }
        }
        if (count($data) > 0) {
            $newData = $this->News->newEntities($data);
            $this->News->saveMany($newData);
            echo 'okay';
        }
    }
}
