<?php

namespace App\Model\Mysql;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'ex_account';
    public $timestamps = true;
    public $incrementing = true;
    protected $fillable = [
        'type', 'description', 'value'
    ];
}
