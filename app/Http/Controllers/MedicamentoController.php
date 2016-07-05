<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\medicamento;

use Illuminate\Support\Facades\DB;

class MedicamentoController extends Controller
{
    public function Medicamentos(){
		$resultado = medicamento::all();
		return $resultado;
	}
}
