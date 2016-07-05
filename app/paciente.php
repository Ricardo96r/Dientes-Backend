<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paciente extends Model
{
    protected $table = 'paciente';
	public $timestamps = false;
	
	
	 public function historial()
    {
        return $this->hasOne('App\historial','id');
    }
	
	public function cita()
    {
        return $this->hasMany('App\cita', 'id_paciente', 'id');
    }
	
	public function consulta()
    {
        return $this->hasMany('App\consulta', 'id_paciente', 'id');
    }
	
		 public function odontologo()
    {
        return $this->hasManyThrough('App\odontologo', 'App\cita', 'paciente_id', 'odontologo_id');
    }
}
