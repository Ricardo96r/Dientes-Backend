<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\diente;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class DienteController extends Controller
{
    public function nuevoDiente(Request $request) {
		 $v = Validator::make($request->all(), [
        'id_paciente' => 'required|exists:paciente,id',
		'diente' => 'required|Integer',
        'seccion' => 'required|Integer',
		'observacion' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$diente = new diente;
			$diente->id_paciente=$request->id_paciente;
			$diente->diente=$request->diente;
			$diente->seccion=$request->seccion;
			$diente->observacion=$request->observacion;
			
			$diente->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
