<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class PERSONDATA extends Model {
  protected $connection = 'firebird';
  protected $table = 'PERSONDATA';
  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = 'ID';
}