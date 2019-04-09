<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    public $timestamps = false;

    protected $primaryKey = "avi_id";

    protected $table = "t_e_avis_avi";

    public $dates = ['avi_date'];

    public function jeuvideo() { return $this->belongsTo('App\Jeuvideo','jeu_id','jeu_id'); }

    public function client() { return $this->belongsTo('App\Client','cli_id','cli_id'); }
    public function clientsabusif() { return $this->belongsToMany('App\Client','t_j_avisabusif_ava','avi_id','cli_id'); }
}
