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
use Spatie\Crawler\Crawler;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Spatie\Crawler\CrawlObserver;
use Cake\Routing\Middleware\RoutingMiddleware;
use DOMDocument;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class demo extends CrawlObserver
{
    private $pages = [];

    public function willCrawl(UriInterface $uri)
    {
        echo "Now crawling: " . (string)$uri . PHP_EOL;
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
        $tag = $doc->getElementsByTagName("h2");
        for ($i = 0; $i < count($tag); $i++) {
            $title = $doc->getElementsByTagName("h2")[$i]->nodeValue;
            $link = $doc->getElementsByTagName("h2")->item($i)->getElementsByTagName("a")->item(0)->getAttribute('href');
            $this->pages[] = [
                'title' => $title,
                'link' => $link
            ];
        }
        dd($this->pages);
        exit;
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
        echo 'Failed';
    }

    public function finishedCrawling()
    {
        echo 'crawled ' . count($this->pages) . ' urls' . PHP_EOL;
        foreach ($this->pages as $page) {
            echo sprintf("Url  path: %s Page title: %s%s", $page['path'], $page['title'], PHP_EOL);
        }
    }
}
