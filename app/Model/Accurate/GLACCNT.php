<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class GLACCNT extends Model
{
    protected $connection = 'firebird';
    protected $table = 'GLACCNT';

    protected $fillable = [
        'GLACCOUNT',
        'CURRENCYID',
        'ACCOUNTNAME',
        'ACCOUNTTYPE',
        'SUBACCOUNT',
        'PARENTACCOUNT',
        'SUSPENDED',
        'MEMO',
        'FIRSTPARENTACCOUNT',
        'INDENTLEVEL',
        'ISFISCAL',
        'ISALLOCTOPROD',
        'TRANSACTIONID',
        'IMPORTEDTRANSACTIONID',
        'BRANCHCODEID',
        'LFT',
        'RGT',
        'ISROOT',
        'NEXTINVOICENO'
    ];
}
