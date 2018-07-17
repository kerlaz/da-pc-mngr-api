<?php

session_start();

require __DIR__ . "/../vendor/autoload.php";

$app = new Slim\App([
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'da_db',
            'username' => 'root',
            'password' => 'n0smi1ez',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'jwt_secret'=>'528C17F906A642F0EB1FB26A7ABBCB60D4B3319979C95F9046D38D2A0FC2FA33'
    ],
]);

$container = $app->getContainer();

$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['AuthController'] = function ($container) {
    return new App\Controllers\AuthController($container);
};
$container["DataController"] = function ($container) {
    return new App\Controllers\DataController($container);
};


require __DIR__ . "/routes.php";
