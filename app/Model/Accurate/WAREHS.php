<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class WAREHS extends Model
{
    protected $connection = 'firebird';
    protected $table = 'WAREHS';

    protected $fillable = [
        'WAREHOUSEID',
        'NAME',
        'DESCRIPTION',
        'ADDRESS1',
        'ADDRESS2',
        'ADDRESS3',
        'SUSPENDED'
    ];
}
