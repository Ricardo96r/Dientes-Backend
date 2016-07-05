<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\tratamiento;

use Illuminate\Support\Facades\DB;

class TratamientoController extends Controller
{
    public function tratamientos() {
		$resultado= tratamiento::select('id', 'nombre')
		->orderBy('nombre', 'asc')
		->get();

        return $resultado;
	}
	
	public function tratamientoMes($mes, $odontologo) {
		$resultado = tratamiento::select(DB::raw("tratamiento.nombre, ROUND(SUM(tratamiento.costo), 0) AS costo"))
		->join('consulta_has_tratamiento', 'consulta_has_tratamiento.id_tratamiento', '=', 'tratamiento.id')
		->join('consulta', 'consulta.id', '=', 'consulta_has_tratamiento.id_consulta')
		->groupBY(DB::raw('consulta.id_odontologo, DATEPART(YEAR,consulta.fecha), DATEPART(month,consulta.fecha),
							tratamiento.nombre'))
		->havingRaw('consulta.id_odontologo='.$odontologo.' and DATEPART(month,consulta.fecha)='.$mes.'')
		->get();
		
		return $resultado;
	}
}
