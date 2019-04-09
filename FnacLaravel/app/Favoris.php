<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Favoris extends Model
{
    public $timestamps = false;

    protected $primaryKey = ["cli_id", "jeu_id"];
    public $incrementing = false;

    protected $table = "t_j_favori_fav";

    public function clients(){
    	return $this->hasMany('App\Client','cli_id','cli_id');
    }

    public function save(array $options = []){
    	if(($row=self::where('cli_id',$this->cli_id)->where('jeu_id',$this->jeu_id))->count()>0){
    		$row->delete();
    	}else{
    		parent::save();
    	}
    }

}
