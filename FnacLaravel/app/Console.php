<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Console extends Model
{
    public $timestamps = false;

    protected $primaryKey = "con_id";

    protected $table = "t_r_console_con";

    public function jeuxvideos(){
    	return $this->hasMany('App\Jeuvideo','con_id','con_id');
    }
}
