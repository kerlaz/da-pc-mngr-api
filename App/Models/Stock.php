<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = [
        'dept_id',
        'group',
        'name',
        'price',
        'balance',
        'part_id',
        'profile',
    ];

}