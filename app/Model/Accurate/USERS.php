<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class USERS extends Model
{
    protected $connection = 'firebird';
    protected $table = 'USERS';
    public $timestamps = false;
    public $incrementing = false;
}
