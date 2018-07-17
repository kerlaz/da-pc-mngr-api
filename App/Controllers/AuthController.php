<?php

namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function auth($req, $res)
    {
        $cr = $req->getParsedBody();
        $user = $this->checkCredentials($cr['login'], $cr['password']);
        if (!$user) {
            $data = ['error' => true, 'message' => 'auth failed'];
        } else {
            $data['error'] = false;
            $data['token'] = $this->makeToken($user, $this->settings['jwt_secret']);
        }
        return $res->withJson($data, null, JSON_UNESCAPED_UNICODE);
    }

    private function checkCredentials($login, $password)
    {
        $user = User::where('email', $login)->first();
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            return [
                'email' => $user->email,
                'level' => $user->level,
                'name' => $user->name,
                'id' => $user->id
            ];
        }
        return false;
    }

    public function checkToken($req,$res)
    {
        $token = $req->getParsedBody()['token'];
        $key = $this->settings['jwt_secret'];
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Exception $exception) {
            $decoded = false;
            $data['msg'] = $exception->getMessage();
        }
        if($decoded && $decoded->iss === "inkoda pharm"){
            $data['error'] = false;
            $data['token'] = $token;
        } else {
            $data['error'] = true;
        }

        return $res->withJson($data,null,JSON_UNESCAPED_UNICODE);
    }

    private function makeToken($user, $key)
    {
        $payload = [
            'iss' => 'inkoda pharm',
            'name' => $user['name'],
            'email' => $user['email'],
            'level' => $user['level'],
            'exp' => time() + 60*60*24,
            'iat' => time()
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}