<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = "pho_id";

    protected $table = "t_e_photo_pho";

    public function jeuvideo(){
    	$this->belongsTo('App\Jeuvideo','jeu_id','jeu_id');
    }
}
