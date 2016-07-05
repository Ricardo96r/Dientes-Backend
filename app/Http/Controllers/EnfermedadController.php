<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\enfermedad;

use Illuminate\Support\Facades\DB;

class EnfermedadController extends Controller
{
    public function enfermedades(){
		$resultado = enfermedad::all();
		return $resultado;
	}
}
