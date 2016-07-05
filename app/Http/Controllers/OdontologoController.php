<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\odontologo;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

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
	
		public function registrarOdontologo(Request $request) {
		 $v = Validator::make($request->all(), [
        'nombre' => 'required|String',
		'segundo_nombre' => 'String',
		'apellido' => 'required|String',
		'segundo_apellido' => 'String',
        'cedula' => 'required|Integer',
		'especialidad' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$odontologo = new odontologo;
			$odontologo->nombre=$request->nombre;
			$odontologo->segundo_nombre=$request->segundo_nombre;
			$odontologo->apellido = $request->apellido;
			$odontologo->segundo_apellido = $request->segundo_apellido;
			$odontologo->cedula = $request->cedula;
			$odontologo->especialidad = $request->especialidad;
			$odontologo->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
