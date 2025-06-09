<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';


use App\Infrastructure\Http\Application;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Factory\Psr17Factory;

use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\Http\PSR7Worker;


$worker = Worker::create();


$factory = new Psr17Factory();

$psr7 = new PSR7Worker($worker, $factory, $factory, $factory);

$app = new Application();

while (true) {
    try {
        $request = $psr7->waitRequest();
        if ($request === null) {
            break;
        }
    } catch (\Throwable $e) {
        $psr7->respond(new Response(400));
        continue;
    }


    try {
        $psr7->respond($app->handle($request));
    } catch (\Throwable $e) {
        $psr7->respond(new Response(500, [], $e->getMessage()));
    }
}