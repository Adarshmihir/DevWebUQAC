<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adresse extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = "adr_id";

    protected $table = 't_e_adresse_adr';

    public function client(){
    	return $this->belongsTo('App\Client','cli_id','cli_id');
	}
    public function pays(){
    	return $this->belongsTo('App\Pays','pay_id','pay_id');
    }

    public function commandes(){
    	return $this->hasMany('App\Commande','adr_id','adr_id');
    }
}
