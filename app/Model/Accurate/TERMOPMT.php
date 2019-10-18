<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class TERMOPMT extends Model
{
    protected $connection = 'firebird';
    protected $table = 'TERMOPMT';

    protected $fillable = [
        'TERMID',
        'DISCPC',
        'DISCDAYS',
        'NETDAYS',
        'TERMNAME',
        'COD',
        'TERMMEMO'
    ];
}
