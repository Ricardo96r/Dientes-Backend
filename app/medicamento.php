<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class medicamento extends Model
{
    protected $table = 'medicamento';
	public $timestamps = false;
	
		public function historial()
    {
        return $this->belongsToMany('App\historial', 'medicamento_has_historial', 'id_medicamento', 'id_paciente');
    }
}
