<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class historial extends Model
{
    protected $table = 'historial';
	public $timestamps = false;
	
	public function alergia()
    {
        return $this->belongsToMany('App\alergia', 'alergia_has_historial', 'id_paciente', 'id_alergia');
    }
	
	public function enfermedad()
    {
        return $this->belongsToMany('App\enfermedad', 'enfermedad_has_historial', 'id_paciente', 'id_enfermedad');
    }
	
	public function medicamento()
    {
        return $this->belongsToMany('App\medicamento', 'medicamento_has_historial', 'id_paciente', 'id_medicamento');
    }
	
	public function diente()
    {
        return $this->hasMany('App\diente', 'id_paciente');
    }
}
