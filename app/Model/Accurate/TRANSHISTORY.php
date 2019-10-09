<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class TRANSHISTORY extends Model
{
    protected $connection = 'firebird';
    protected $table = 'TRANSHISTORY';
    public $timestamps = false;
    public $incrementing = false;
}
