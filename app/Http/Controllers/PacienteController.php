<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\paciente;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function clientesPorEdad()
    {
		$paciente = paciente::select( DB::raw("DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento) AS edad, count(*) AS clientes"))
		->groupBY(DB::raw("DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento)"))
		->get();
		return $paciente;
    }
	
	
}
