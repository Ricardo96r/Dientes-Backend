<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cita extends Model
{
    protected $table = 'cita';
	public $timestamps = false;
	
	public function paciente()
    {
        return $this->belongsTo('App\paciente', 'id', 'id_paciente');
    }
	
	public function odontologo()
    {
        return $this->belongsTo('App\odontologo', 'id', 'id_odontologo');
    }

}
