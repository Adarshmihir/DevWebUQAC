<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    public $timestamps = false;

    protected $primaryKey = "pay_id";

    protected $table = "t_r_pays_pay";

    public function adresses(){
    	return $this->hasMany('App\Adresse','pay_id','pay_id');
    }

    public function relais(){
    	return $this->hasMany('App\Relais','pay_id','pay_id');
    }
}
