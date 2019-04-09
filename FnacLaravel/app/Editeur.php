<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Editeur extends Model
{
    public $timestamps = false;

    protected $primaryKey = "edi_id";

    protected $table = "t_r_editeur_edi";

    public function jeuxvideos(){
    	return $this->hasMany('App\Jeuvideos','edi_id','edi_id');
    }
}
