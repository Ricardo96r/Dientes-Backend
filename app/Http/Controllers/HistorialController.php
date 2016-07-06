<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\historial;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

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
	
	public function registrarHistorial(Request $request) {
		 $v = Validator::make($request->all(), [
        'id' => 'required|exists:paciente,id',
		'ultima_visita_al_odontologo' => 'date',
		'aprieta_los_dientes' => 'required|Boolean',
		'dolor_de_dientes' => 'required|Boolean',
		'observacion_dolor' => 'String',
		'sangrado_de_encias' => 'required|Boolean',
		'observacion_sangrado' => 'String',
		'ruido_al_mover_la_mandibula' => 'required|Boolean',
		'observacion_ruidos' => 'String',
		'fuma' => 'required|Boolean',
		'cigarrillos_diarios' => 'Integer',
		'muerde_objetos_extranos' => 'required|Boolean',
		'muerde_las_unas' => 'required|Boolean',
		'experiencia_dental_negativa' => 'required|Boolean',
		'instruido_en_cepillado' => 'required|Boolean',
		'embarazo' => 'Boolean',
		'ciclo_menstrual_regular' => 'Boolean',
		'toma_anticonceptivos' => 'Boolean'
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$historial = new historial;
			$historial->id=$request->id;
			$historial->fecha_creacion=Carbon::now();
			$historial->ultima_visita_al_odontologo=$request->ultima_visita_al_odontologo;
			$historial->aprieta_los_dientes = $request->aprieta_los_dientes;
			$historial->dolor_de_dientes = $request->dolor_de_dientes;
			$historial->observacion_dolor=$request->observacion_dolor;
			$historial->sangrado_de_encias=$request->sangrado_de_encias;
			$historial->observacion_sangrado = $request->observacion_sangrado;
			$historial->ruido_al_mover_la_mandibula = $request->ruido_al_mover_la_mandibula;
			$historial->observacion_ruidos = $request->observacion_ruidos;
			$historial->fuma = $request->fuma;
			$historial->cigaros_diarios=$request->cigarros_diarios;
			$historial->muerde_objetos_extranos = $request->muerde_objetos_extranos;
			$historial->muerde_las_unas = $request->muerde_las_unas;
			$historial->experiencia_dental_negativa=$request->experiencia_dental_negativa;
			$historial->instruido_en_cepillado=$request->instruido_en_cepillado;
			$historial->embarazo = $request->embarazo;
			$historial->ciclo_menstrual_regular = $request->ciclo_menstrual_regular;
			$historial->toma_anticonceptivos = $request->toma_anticonceptivos;

			$historial->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
