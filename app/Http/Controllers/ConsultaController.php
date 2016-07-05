<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\consulta;

use App\tratamiento;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class ConsultaController extends Controller
{
	public function detallesConsulta($id_consulta){
		$resultado=consulta::leftJoin('consulta_has_tratamiento', 'consulta.id', '=', 'consulta_has_tratamiento.id_consulta')
		->leftJoin('tratamiento', 'consulta_has_tratamiento.id_tratamiento', '=', 'tratamiento.id')
        ->join('odontologo', 'consulta.id_odontologo', '=', 'odontologo.id')
		->where('consulta.id', '=', $id_consulta)
        ->select(DB::raw("consulta.id, consulta.observaciones, consulta.fecha, tratamiento.nombre as tratamiento, tratamiento.detalles as detalles_tratamiento, CONCAT(odontologo.nombre,' ',odontologo.apellido) as odontologo"))
        ->get();
		return $resultado;
	}
	
   public function consultasPaciente($id_paciente){
		$resultado=consulta::leftJoin('consulta_has_tratamiento', 'consulta.id', '=', 'consulta_has_tratamiento.id_consulta')
		->leftJoin('tratamiento', 'consulta_has_tratamiento.id_tratamiento', '=', 'tratamiento.id')
        ->join('odontologo', 'consulta.id_odontologo', '=', 'odontologo.id')
		->where('consulta.id_paciente', '=', $id_paciente)
        ->select(DB::raw("consulta.*, tratamiento.*, CONCAT(odontologo.nombre,' ',odontologo.apellido) as odontologo"))
        ->get();

		return $resultado;
	}
	
	public function consultasOdontologoPaciente($id_odontologo, $id_paciente) {
		$resultado=consulta::select(DB::raw("consulta.*, tratamiento.*, CONCAT(odontologo.nombre,' ',odontologo.apellido) as odontologo"))
		->leftJoin('consulta_has_tratamiento', 'consulta_has_tratamiento.id_consulta', '=', 'consulta.id')
		->leftJoin('tratamiento', 'consulta_has_tratamiento.id_tratamiento', '=', 'tratamiento.id')
		->leftJoin('odontologo','odontologo.id','=', 'consulta.id_odontologo')
		->where('consulta.id_paciente','=',$id_paciente)
		->where('consulta.id_odontologo','=',$id_odontologo)
		->get();
		return $resultado;
	}
	
	public function registrarConsulta(Request $request) {
		 $v = Validator::make($request->all(), [
        'observaciones' => 'required|string',
        'fecha' => 'required|date',
		'id_paciente' => 'required|exists:paciente,id',
        'id_odontologo' => 'required|exists:odontologo,id',
		'id_tratamiento' => 'required|exists:tratamiento,id'
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$consulta = new consulta;
			$consulta->id_paciente=$request->id_paciente;
			$consulta->id_odontologo=$request->id_odontologo;
			$consulta->observaciones = $request->observaciones;
			$consulta->fecha = $request->fecha;
			$consulta->save();
			
			$tratamiento = tratamiento::find($request->id_tratamiento);
			$tratamiento->consulta()->attach($consulta->id);
			
			
			return response()->json(['resultado' => 'exito']);
		}
	}
	
	public function pacientesMes($mes) {
        return DB::select("
            SELECT b.id_odontologo, CONCAT(b.nombre,' ', b.segundo_nombre,' ', b.apellido,' ', b.segundo_apellido) AS nombre, b.cedula, b.especialidad, q.clientes 
            FROM(
                SELECT id_odontologo, COUNT(DISTINCT id_paciente) AS clientes
                FROM consulta
                GROUP BY id_odontologo, DATEPART(YEAR,fecha), DATEPART(month,fecha)
                HAVING COUNT(DISTINCT id_paciente)=(
                    SELECT MAX(clientes) 
                    FROM(
                        SELECT id_odontologo AS id, DATEPART(MONTH,fecha) AS mes, COUNT(DISTINCT id_paciente) AS clientes
                        FROM consulta
                        GROUP BY id_odontologo, DATEPART(YEAR,fecha), DATEPART(month,fecha)
                        HAVING DATEPART(YEAR,GETDATE())= DATEPART(YEAR,fecha) AND DATEPART(MONTH,fecha)='$mes'
                    ) AS query1
                )
            ) AS q INNER JOIN odontologo b ON q.id_odontologo=b.id_odontologo;
        ");
    }
	
	public function ingresosMes($mes) {
        return DB::select("SELECT b.id_odontologo, CONCAT(b.nombre,' ', b.segundo_nombre,' ', b.apellido,' ',
							b.segundo_apellido) AS nombre, b.cedula, b.especialidad, ROUND(q.ingresos, 1) AS ingresos
							FROM(
							SELECT a.id_odontologo, SUM(b.costo) AS ingresos
							FROM consulta a INNER JOIN factura b ON a.id_consulta=b.id_consulta
							GROUP BY a.id_odontologo, DATEPART(YEAR,a.fecha), DATEPART(month,a.fecha)
							HAVING SUM(b.costo)=(
							SELECT MAX(ingresos)
							FROM(
							SELECT a.id_odontologo AS id, DATEPART(MONTH,a.fecha) AS mes,
							SUM(b.costo) AS ingresos
							FROM consulta a INNER JOIN factura b ON a.id_consulta=b.id_consulta
							GROUP BY a.id_odontologo, DATEPART(YEAR,a.fecha),
							DATEPART(month,a.fecha)
							HAVING DATEPART(YEAR,GETDATE())= DATEPART(YEAR,a.fecha) AND
							DATEPART(MONTH,a.fecha)='$mes'
							) AS query1
							)
							) AS q INNER JOIN odontologo b ON q.id_odontologo=b.id_odontologo;");
    }
}
