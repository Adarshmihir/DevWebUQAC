<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvisAbusif extends Model
{
    public $timestamps = false;

    protected $primaryKey = ['cli_id', 'avi_id'];
    public $incrementing = false;

    protected $table = "t_j_avisabusif_ava";

    public function avis(){
    	return $this->hasOne('App\Avis','avi_id','avi_id');
    }

    public function client(){
    	return $this->hasOne('App\Client','cli_id','cli_id');
    }

    public function save(array $options = []) {
    	if ((new static)->where('cli_id', $this->cli_id)->where('avi_id', $this->avi_id)->count() > 0)
    		return false;
    	return parent::save($options);

    }
}
