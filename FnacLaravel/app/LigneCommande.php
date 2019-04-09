<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    public $timestamps = false;

    protected $primaryKey = ["com_id", "jeu_id"];

    protected $table = "t_j_lignecommande_lec";
}
