<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paciente extends Model
{
    protected $table = 'paciente';
	protected $primaryKey = 'paciente_id';
	public $timestamps = false;
}
