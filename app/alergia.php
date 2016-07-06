<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class alergia extends Model
{
    protected $table = 'alergia';
	public $timestamps = false;
	
	public function historial()
    {
        return $this->belongsToMany('App\historial', 'alergia_has_historial', 'id_alergia', 'id_paciente');
    }
}
