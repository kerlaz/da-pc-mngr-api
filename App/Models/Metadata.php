<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    protected $table = 'metadata';

    protected $fillable = [
        'nameid',
        'name',
        'bestimg',
        'confirmed',
        'metastack',
        'errors',
        'comments',
        'bestnote'
    ];
}