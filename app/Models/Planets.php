<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planets extends Model
{
    protected $table = 'planetas';

    protected $fillable = [
        'id',
        'nome',
        'clima',
        'terreno',
        'cnt_aparicoes',
    ];

    //deactivate because the test can't use automatic insert or create table
    public $timestamps = false;
    
}
