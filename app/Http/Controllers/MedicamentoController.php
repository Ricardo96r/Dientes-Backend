<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\medicamento;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class MedicamentoController extends Controller
{
    public function Medicamentos(){
		$resultado = medicamento::all();
		return $resultado;
	}
	
	public function nuevoMedicamento(Request $request) {
		 $v = Validator::make($request->all(), [
        'nombre' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$medicamento = new medicamento;
			$medicamento->nombre=$request->nombre;
			
			$medicamento->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
