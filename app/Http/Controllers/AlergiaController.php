<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\alergia;

use Illuminate\Support\Facades\DB;

class AlergiaController extends Controller
{
    public function alergias(){
		$resultado = alergia::all();
		return $resultado;
	}
}
