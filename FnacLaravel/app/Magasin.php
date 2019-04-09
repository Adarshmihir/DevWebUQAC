<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Magasin extends Model
{
    public $timestamps = false;

    protected $primaryKey = "mag_id";

    protected $table = "t_r_magasin_mag";

    public function clients(){
    	return $this->hasMany('App\Client','mag_id','mag_id');
    }

    public function commandes(){
    	return $this->hasMany('App\Commande','mag_id','mag_id');
    }
}
