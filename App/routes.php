<?php


$app->options('/{routes:.+}', function ($req, $res, $args) {
    return $res;
});
//
$app->add(function ($req, $res, $next) {
    $res = $next($req, $res);
    return $res
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});



$app->get("/","DataController:index");

$app->post("/auth","AuthController:auth");
$app->post("/token","AuthController:checkToken");

$app->get("/api/getrandom","DataController:getRandom");
$app->get("/api/getnote/{note_id}","DataController:getNote");

$app->post("/api/set/meta","DataController:setMeta");
$app->post("/api/set/error","DataController:setError");


