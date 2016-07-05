<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\odontologo;

use Illuminate\Support\Facades\DB;

class OdontologoController extends Controller
{
    public function odontologos() {
		$resultado= odontologo::select(DB::raw("id, CONCAT(nombre, ' ', apellido) AS nombre"))
		->orderBy('nombre', 'asc')
		->get();
		
        return $resultado;
	}
	
	public function detallesOdontologo($id_odontologo){
		$resultado=odontologo::select(DB::raw("id, CONCAT(nombre, ' ', apellido) AS nombre, cedula, especialidad"))
		->where('id','=',$id_odontologo)
		->get();
        return $resultado;
	}
}
