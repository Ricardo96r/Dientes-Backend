<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\medicamento;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class MedicamentoController extends Controller
{
    public function medicamentos(){
		$resultado = medicamento::select('medicamento.nombre','medicamento.id')
		->orderBy('medicamento.nombre')
		->get();
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
	
	public function anexarMedicamento(Request $request) {
		 $v = Validator::make($request->all(), [
		 
        'id_medicamento' => 'required|exists:Medicamento,id',
		'id_paciente' => 'required|exists:Historial,id',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$medicamento = medicamento::find($request->id_medicamento);
			$medicamento->historial()->attach($request->id_paciente);
			return response()->json(['resultado' => 'exito']);
		}
	}
}
