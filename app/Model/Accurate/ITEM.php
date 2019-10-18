<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class ITEM extends Model
{
    protected $connection = 'firebird';
    protected $table = 'ITEM';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'ITEMNO';

    protected $fillable = [
        'ITEMNO',
        'ITEMDESCRIPTION',
        'ITEMTYPE',
        'NOTES',
        'QUANTITY',
        'UNITPRICE',
        'UNITPRICE2',
        'UNITPRICE3',
        'UNITPRICE4',
        'UNITPRICE5'
    ];
}
