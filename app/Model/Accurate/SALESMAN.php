<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SALESMAN extends Model
{
    protected $connection = 'firebird';
    protected $table = 'SALESMAN';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'SALESMANID';

    protected $fillable = [
        'SALESMANID',
        'LASTNAME',
        'FIRSTNAME',
        'JOBTITLE',
        'SALESMANNAME',
        'BRANCHCODEID',
        'TRANSACTIONID'
    ];
}
