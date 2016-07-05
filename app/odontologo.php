<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class odontologo extends Model
{
    protected $table = 'odontologo';
	public $timestamps = false;
	
	public function consulta()
    {
        return $this->hasMany('App\consulta', 'id_odontologo', 'id');
    }
	
	public function cita()
    {
        return $this->hasMany('App\cita', 'id_odontologo', 'id');
    }
	

}
