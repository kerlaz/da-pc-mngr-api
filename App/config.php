<?php

session_start();

require __DIR__ . "/../vendor/autoload.php";

$db['dev'] = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'da_db',
    'username' => 'root',
    'password' => 'n0smi1ez',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];
$db['prod'] = [
    'driver' => 'mysql',
    'host' => 'srv-pleskdb17.ps.kz:3306',
    'database' => 'dobrayaa_slim',
    'username' => 'dobra_user',
    'password' => 'Geed43*8',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];

$app = new Slim\App([
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'db' => $db['prod'],
        'jwt_secret' => 'SECRET_KEY'
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
