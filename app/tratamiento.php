<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tratamiento extends Model
{
    protected $table = 'tratamiento';
	public $timestamps = false;
	
	public function consulta()
    {
        return $this->belongsToMany('App\consulta', 'consulta_has_tratamiento', 'id_tratamiento', 'id_consulta');
    }
}
