<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class GLHIST extends Model
{
    protected $connection = 'firebird';
    protected $table = 'GLHIST';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'GLHISTID',
        'SEQ',
        'GLACCOUNT',
        'GLYEAR',
        'GLPERIOD',
        'BASEAMOUNT',
        'PRIMEAMOUNT',
        'SOURCE',
        'TRANSTYPE',
        'TRANSDATE',
        'TRANSDESCRIPTION',
        'INVOICEID',
        'PERSONID',
        'USERID',
    ];
}
