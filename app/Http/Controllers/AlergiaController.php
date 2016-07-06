<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\alergia;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class AlergiaController extends Controller
{
    public function alergias(){
		$resultado = alergia::all();
		return $resultado;
	}
	
	public function nuevaAlergia(Request $request) {
		 $v = Validator::make($request->all(), [
        'nombre' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$alergia = new alergia;
			$alergia->nombre=$request->nombre;
			
			$alergia->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
