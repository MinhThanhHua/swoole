<?php
namespace App\Shell;

use Cake\Console\Shell;
use App\Model\Table\NewsTable;

/**
 * CrawMultipleDataShell shell command.
 *
 * @property NewsTable $News
 */
class CrawMultipleDataShell extends Shell
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
        $this->loadModel('News');
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
        for ($count = 0; $count < TOTAL; $count++) {
            try {
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
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        var_dump(microtime(true) - $time);
    }
}
