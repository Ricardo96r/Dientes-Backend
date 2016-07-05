<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\factura;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

class FacturaController extends Controller
{
    public function facturas(){
		$resultado=factura::select('id', 'fecha');
		return $resultado;
	}
	
	public function factura($id_consulta){
		$resultado=factura::findOrFail($id_consulta);
		return $resultado;
	}
	
	public function facturar(Request $request) {
		 $v = Validator::make($request->all(), [
        'id_consulta' => 'required|exists:consulta,id|unique:factura,id',
		'costo' => 'required|Integer',
        'forma_de_pago' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$factura = new factura;
			$factura->id=$request->id_consulta;
			$factura->costo=$request->costo;
			$factura->forma_de_pago=$request->forma_de_pago;
			$factura->fecha=Carbon::now();
			$factura->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
