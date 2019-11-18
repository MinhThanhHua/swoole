<?php
$server = new swoole_http_server("192.168.33.10", 1235);

$server->on('start', function () {
    echo "server is started\n";
});

$server->on('request', function ($request, $response) {
      $response->header('Content-type', 'text/html');
   $response->end(file_get_contents('/var/www/html/test.html'));
});

$server->start();
