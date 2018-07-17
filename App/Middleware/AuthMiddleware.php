<?php

namespace App\Middleware;

use Firebase\JWT\JWT;

class AuthMiddleware extends Middleware
{
    public function __invoke($request,$response,$next)
    {
        if($this->checkToken($request->getHeader('Authorization'))){
            $response = $next($request, $response);
            return $response;
        } else {
            return $response->withJson(['error'=>true,'message'=>'Invalid token'],null,JSON_UNESCAPED_UNICODE);
        }

    }
    private function checkToken($token)
    {
        if($token[0]){
            $key = $this->settings['jwt_secret'];
            try {
                $decoded = JWT::decode($token[0], $key, array('HS256'));
            } catch (\Exception $exception) {
                return false;
            }
            if($decoded && $decoded->iss === "inkoda pharm"){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}