<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class consulta extends Model
{
    protected $table = 'consulta';
	public $timestamps = false;
	
	public function tratamiento()
    {
        return $this->belongsToMany('App\tratamiento', 'consulta_has_tratamiento', 'id_consulta', 'id_tratamiento');
    }
	
	public function factura()
    {
        return $this->hasOne('App\factura','id');
    }
}
