<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;
use DOMDocument;
use DomXPath;
use Cake\Http\Session;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class getTitle extends CrawlObserver
{
    private $data = [];
    private $Session;
    private $fileName;

    public function __construct($fileName) {
        $this->Session = new Session();
        $this->fileName = $fileName;
    }

    public function willCrawl(UriInterface $uri)
    {
//        echo "Now crawling: " . (string)$uri . PHP_EOL;
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($response->getBody());
        $xpath = new DomXPath($doc);
        $classname = 'title';
        $nodeList = $xpath->query("//*[@class='" . $classname . "']");
        for ($j = 0; $j < $nodeList->length; $j++) {
            $text = $nodeList->item($j)->getElementsByTagName('a')->item(0)->nodeValue;
        }
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
//        echo 'Failed';
        echo ($requestException);
    }

    public function finishedCrawling()
    {
        if (file_exists(PATH_FILE_CSV . "/$this->fileName")) {
            $this->outputCsvCustom($this->fileName, $this->data, true);
        } else {
            $this->outputCsvCustom($this->fileName, $this->data);
        }
    }

    private function outputCsvCustom($fileName, $dataArr, $edit = false)
    {
        header('Content-Type: text/csv; charset:SJIS-win');
        $fp = fopen($fileName, ($edit) ? 'a' : 'w');
        foreach ($dataArr as $dataEach) {
            $dataEncode = [mb_convert_encoding($dataEach, 'UTF-8')];
            fputcsv($fp, $dataEncode);
        }
        fclose($fp);
    }
}
