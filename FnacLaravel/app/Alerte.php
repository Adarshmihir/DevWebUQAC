<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = ["cli_id", "jeu_id"];

    protected $table = "t_j_alerte_ale";

    public function clients(){
    	return $this->hasMany('App\Client','cli_id','cli_id');
    }

    public function jeuxvideos(){
    	return $this->hasMany('App\Jeuvideo','jeu_id','jeu_id');
    }
}