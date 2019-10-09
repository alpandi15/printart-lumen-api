<?php

namespace App\Model\Mysql;

use Illuminate\Database\Eloquent\Model;

class Increment extends Model
{
    protected $table = 'increment';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'type', 'description', 'value'
    ];
}
