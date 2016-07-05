<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\cita;

use App\paciente;

use App\odontologo;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
	public function cita($id_cita){
		$resultado=cita::select(DB::raw("cita.id,CONCAT(paciente.nombre,' ', paciente.apellido) AS paciente, CONCAT(odontologo.nombre,' ', odontologo.apellido) AS odontologo, cita.motivo, cita.fecha"))
		->join('paciente', 'cita.id_paciente', '=', 'paciente.id')
		->join('odontologo', 'cita.id_odontologo','=','odontologo.id')
		->where('cita.id','=',$id_cita)
		->get();
		return $resultado;
	}
	
    public function citasOdontologo($id_odontologo) {
		$resultado=cita::select(DB::raw("CONCAT(paciente.nombre,' ', paciente.apellido) AS paciente, cita.motivo, cita.fecha"))
		->join('paciente', 'cita.id_paciente', '=', 'paciente.id')
		->where('id_odontologo','=', $id_odontologo)
		->whereraw("DATEPART(year,cita.fecha)=DATEPART(year,GETDATE())")
		->whereraw("((DATEPART(mm,cita.fecha)=DATEPART(mm,GETDATE())
		AND DATEPART(dd,cita.fecha)>DATEPART(dd,GETDATE()))OR DATEPART(mm,cita.fecha)>DATEPART(mm,GETDATE()))")
		->get();
        return $resultado;
	}
	
	public function citasPaciente($id_paciente) {
		$resultado=cita::select(DB::raw("CONCAT(paciente.nombre,' ', paciente.apellido) AS paciente, cita.motivo, cita.fecha"))
		->join('paciente', 'cita.id_paciente', '=', 'paciente.id')
		->where('id_paciente','=', $id_paciente)
		->whereraw("DATEPART(year,cita.fecha)=DATEPART(year,GETDATE())")
		->whereraw("((DATEPART(mm,cita.fecha)=DATEPART(mm,GETDATE())
		AND DATEPART(dd,cita.fecha)>DATEPART(dd,GETDATE()))OR DATEPART(mm,cita.fecha)>DATEPART(mm,GETDATE()))")
		->get();
        return $resultado;
	}
	
	public function citasDia() {
		$resultado=cita::select(DB::raw("CONCAT(odontologo.nombre,' ', odontologo.apellido) AS odontologo,
		CONCAT(paciente.nombre,' ', paciente.apellido) AS paciente, cita.motivo,
		CONCAT(DATEPART(hh, cita.fecha),':',DATEPART(mi, cita.fecha)) AS hora"))
		->join('paciente', 'cita.id_paciente', '=', 'paciente.id')
		->join('odontologo', 'cita.id_odontologo','=','odontologo.id')
		->whereraw("DATEPART(dd,cita.fecha)=DATEPART(dd,GETDATE())")
		->whereraw("DATEPART(mm,cita.fecha)=DATEPART(mm,GETDATE())")
		->whereraw("DATEPART(year,cita.fecha)=DATEPART(year,GETDATE())")
		->orderBY('hora')
		->get();
        return $resultado;
	}
	
	public function citasMes() {
		$resultado=cita::select(DB::raw("CONCAT(odontologo.nombre,' ', odontologo.apellido) AS odontologo,
		CONCAT(paciente.nombre,' ', paciente.apellido) AS paciente, cita.motivo, DATEPART(dd,cita.fecha) AS dia,
		CONCAT(DATEPART(hh, cita.fecha),':',DATEPART(mi, cita.fecha)) AS hora"))
		->join('paciente', 'cita.id_paciente', '=', 'paciente.id')
		->join('odontologo', 'cita.id_odontologo','=','odontologo.id')
		->whereraw("DATEPART(mm,cita.fecha)=DATEPART(mm,GETDATE())")
		->whereraw("DATEPART(year,cita.fecha)=DATEPART(year,GETDATE())")
		->orderBY('dia', 'asc')
		->orderBY('hora', 'asc')
		->get();
		
		
        return $resultado;
	}
	
	public function registrarCita(Request $request) {
		 $v = Validator::make($request->all(), [
        'motivo' => 'required|string',
        'fecha' => 'required|date',
		'id_paciente' => 'required|exists:paciente,id',
        'id_odontologo' => 'required|exists:odontologo,id',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{			
			$cita = new cita;
			$cita->id_paciente=$request->id_paciente;
			$cita->id_odontologo=$request->id_odontologo;
			$cita->motivo = $request->motivo;
			$cita->fecha = $request->fecha;
			$cita->save();
			return response()->json(['resultado' => 'exito']);
		}
	}
}
