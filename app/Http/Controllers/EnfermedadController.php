<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\enfermedad;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class EnfermedadController extends Controller
{
    public function enfermedades(){
		$resultado = enfermedad::all();
		return $resultado;
	}
	
	public function nuevaEnfermedad(Request $request) {
		 $v = Validator::make($request->all(), [
        'nombre' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$enfermedad = new enfermedad;
			$enfermedad->nombre=$request->nombre;
			
			$enfermedad->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
	
	
}
