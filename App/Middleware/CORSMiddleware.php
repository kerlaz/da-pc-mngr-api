<?php
/**
 * Created by PhpStorm.
 * User: kerla
 * Date: 16.07.2018
 * Time: 15:51
 */

namespace App\Middleware;


class CORSMiddleware extends Middleware
{
    public function __invoke($request,$response,$next)
    {
        $response = $next($request, $response);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}