<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenreJeu extends Model
{
    public $timestamps = false;

    protected $primaryKey = ["jeu_id", "gen_id"];

    public $incrementing = false;

    protected $table = "t_j_genrejeu_gej";
}
