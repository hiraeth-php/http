<?php

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter as Emitter;

//
// Track backwards until we discover our composer.json.
//

for (
	$root_path  = __DIR__;
	$root_path != '/' && !is_file($root_path . DIRECTORY_SEPARATOR . 'composer.json');
	$root_path  = realpath($root_path . DIRECTORY_SEPARATOR . '..')
);

if ($root_path) {
	$req_path = parse_url($_SERVER['REQUEST_URI'])['path'] ?? '/';
	$loader   = require $root_path . '/vendor/autoload.php';
	$hiraeth  = new Hiraeth\Application($root_path);

	if (PHP_SAPI == 'cli-server' && is_file(__DIR__ . $req_path)) {
		return FALSE;
	}

	$hiraeth->exec(function(Handler $handler, Request $request, Emitter $emitter) {
		return $emitter->emit($handler->handle($request)) ? 0 : 1;
	});
}
