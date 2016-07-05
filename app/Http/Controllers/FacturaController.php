<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\factura;

use Illuminate\Support\Facades\DB;

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
}
