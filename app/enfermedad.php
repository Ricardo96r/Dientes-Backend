<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class enfermedad extends Model
{
    protected $table = 'enfermedad';
	public $timestamps = false;
	
		public function historial()
    {
        return $this->belongsToMany('App\historial', 'enfermedad_has_historial', 'id_enfermedad', 'id_paciente');
    }
}
