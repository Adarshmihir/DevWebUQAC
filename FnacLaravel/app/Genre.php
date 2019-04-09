<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public $timestamps = false;

    protected $primaryKey = "gen_id";

    protected $table = "t_r_genre_gen";

    public function jeuxvideos(){
    	return $this->belongsToMany('App\Jeuvideo','t_j_genrejeu_gej','gen_id','jeu_id');
    	}
}
