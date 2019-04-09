<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelaisClient extends Model
{
    public $timestamps = false;

    protected $primaryKey = ["cli_id", "rel_id"];

    protected $table = "t_j_relaisclient_rec";
}
