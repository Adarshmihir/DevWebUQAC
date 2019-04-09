<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relais extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = "rel_id";

    protected $table = "t_e_relais_rel";

    public function commandes(){
    	return $this->hasMany('App\Commande','rel_id','rel_id');
    }

    public function pays(){
    	return $this->belongsTo('App\Pays','pay_id','pay_id');
    }
}
