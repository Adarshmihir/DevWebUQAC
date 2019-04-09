<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = "com_id";

    protected $table = "t_e_commande_com";

    public function relais(){
    	return $this->belongsTo('App\Relais','rel_id','rel_id');
    }

    public function adresse(){
    	return $this->hasOne('App\Adresse','adr_id','adr_id');
    }

    public function client(){
    	return $this->hasOne('App\Client','cli_id','cli_id');
    }

    public function magasin(){
        return $this->belongsTo('App\Magasin','mag_id','mag_id');
    }
}
