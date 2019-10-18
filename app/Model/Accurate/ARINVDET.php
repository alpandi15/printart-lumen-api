<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class ARINVDET extends Model
{
    protected $connection = 'firebird';
    protected $table = 'ARINVDET';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['QUANTITY'];

    public function item(){
        return $this->hasOne(ITEM::class, 'ITEMNO');
    }
    public function ARINV(){
        return $this->hasOne(ARINV::class,'ARINVOICEID', 'ARINVOICEID');
    }
}
