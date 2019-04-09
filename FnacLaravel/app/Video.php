<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    
    public $timestamps = false;

    protected $primaryKey = "vid_id";

    protected $table = 't_e_video_vid';

    public function jeuvideo(){
    	return $this->hasOne('App\Jeuvideo','jeu_id','jeu_id');
    }

    public function jeuvideo(){
    	return $this->belongsTo('App\Jeuvideo','jeu_id','jeu_id');
    }
}
