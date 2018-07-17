<?php

namespace App\Controllers;

use App\Models\Metadata;
use Illuminate\Database\Capsule\Manager as DB;
use stdClass;

class DataController extends Controller
{
    private $DIFF_QUERY = 'select distinct s.name_id, s.name from stock as s where not exists 
(select nameid from metadata as m where s.name_id = m.nameid) order by name_id';
    public function index($req, $res)
    {
        $key = $this->settings['jwt_secret'];
        $data = new stdClass();
        $data->hello = 'world!';
        $data->token = $key;
        return $res->withJson($data);
    }

    public function getRandom($req, $res)
    {
//        select nameid, name, metastack from da_db.metadata where confirmed = 0 and metastack is not null
        $rows = Metadata::select('nameid','name','metastack')
            ->where('confirmed','<',1)
            ->whereNotNull('metastack')
            ->get();
        $random = rand(0,count($rows)-1);
        $data[0] = $rows[$random];
        $data[0]->metastack = json_decode($data[0]->metastack);
        return $res->withJson($data,null,JSON_UNESCAPED_UNICODE);
    }

    public function getNote($req, $res)
    {
        $id = $req->getAttribute('route')->getArgument('note_id');
        $path = __DIR__ . "/../../resources/annotations/parsed/".$id.".html";
        if(file_exists($path)){
            $data = file_get_contents($path);
        } else {
            $data = '<code>Нет данных</code>';
        }
        return $res->write($data);
    }

    public function updateMetadata($req, $res)
    {
        $diff = DB::select($this->DIFF_QUERY);
        return $res->withJson($diff,null,JSON_UNESCAPED_UNICODE);
    }

    public function setMeta($req,$res)
    {
        $meta = $req->getParsedBody();
//        $status = Metadata::update(['confirmed'=>-1,'bestimg'=>$meta['image'],'bestnote'=>$meta['annotation']])->where('nameid',$meta['nameid']);
        try {
            $status['db'] = Metadata::where('nameid',$meta['nameid'])->update(['confirmed'=>-1,'bestimg'=>$meta['image'],'bestnote'=>$meta['annotation']]);
            $status['error'] = false;
        } catch (\Exception $exception) {
            $status['db'] = $exception;
            $status['error'] = true;
        }
        return $res->withJson($status,null,JSON_UNESCAPED_UNICODE);
    }
    public function setError($req,$res){
        $meta = $req->getParsedBody();
        $comment['date'] = date("Y-m-d H:i:s");
        switch ($meta['error']){
            case 1: $comment['message'] = "Нет аннотации"; break;
            case 2: $comment['message'] = "Нет варианта"; break;
            case 3: $comment['message'] = "Некачественное изображение"; break;
            default: $comment['message'] = "Прочие ошибки"; break;
        }
        try {
            $status['db'] = Metadata::where('nameid',$meta['nameid'])->update(['confirmed'=>-1,'errors'=>(int)$meta['error'],'comments'=>json_encode($comment,JSON_UNESCAPED_UNICODE)]);
            $status['error'] = false;
        } catch (\Exception $exception) {
            $status['db'] = $exception;
            $status['error'] = true;
        }
        return $res->withJson($status,null,JSON_UNESCAPED_UNICODE);
    }
}