<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin/ebooks/1/preview';
$_SERVER['SERVER_NAME'] = 'settleanz.test';
$_SERVER['SERVER_PORT'] = 80;
$_SERVER['HTTP_HOST'] = 'settleanz.test';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Headers: ' . PHP_EOL;
foreach ($response->headers->all() as $k => $v) {
    echo '  ' . $k . ': ' . implode(', ', $v) . PHP_EOL;
}
$body = $response->getContent();
echo 'Content-Length: ' . strlen($body) . PHP_EOL;
echo 'Body (first 500): ' . substr($body, 0, 500) . PHP_EOL;

$kernel->terminate($request, $response);
