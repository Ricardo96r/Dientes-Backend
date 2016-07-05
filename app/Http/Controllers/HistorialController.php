<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\historial;

use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    public function alergiasPaciente($id_paciente) {
		$resultado= historial::find($id_paciente)->alergia;
        return $resultado;
	}
	
	public function enfermedadesPaciente($id_paciente) {
		$resultado= historial::find($id_paciente)->enfermedad;
        return $resultado;
	}
	
	public function medicamentosPaciente($id_paciente) {
		$resultado= historial::find($id_paciente)->medicamento;
        return $resultado;
	}
	
		public function dientesPaciente($id_paciente) {
		$resultado= historial::find($id_paciente)->diente;
        return $resultado;
	}
}
