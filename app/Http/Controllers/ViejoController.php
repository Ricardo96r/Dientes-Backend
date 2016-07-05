<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Query;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ViejoController extends Controller
{
	
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
	
	
	

	
	
	
	public function facturar(Request $request) {
		 $v = Validator::make($request->all(), [
        'id_consulta' => 'required|exists:consulta,id_consulta|unique:factura,id_consulta',
		'costo' => 'required|Integer',
        'forma_de_pago' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			DB::insert("INSERT INTO factura (id_consulta, fecha, costo, forma_de_pago)
			VALUES ('$request->id_consulta', GETDATE(), '$request->costo', '$request->forma_de_pago');");
			return response()->json(['resultado' => 'exito']);
		}
	}
	
	public function registrarPaciente(Request $request) {
		 $v = Validator::make($request->all(), [
        'nombre' => 'required|String',
		'segundo_nombre' => 'String',
		'apellido' => 'required|String',
		'segundo_apellido' => 'String',
		'genero' => 'required|String',
		'fecha_nacimiento' => 'required|date',
        'cedula' => 'required|Integer',
		'ocupacion' => 'required|String',
		'telefono' => 'required|min:11|numeric',
		'telefono_emergencias' => 'required|min:11|numeric'
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			DB::insert("INSERT INTO paciente (nombre, segundo_nombre, apellido, segundo_apellido, genero, fecha_nacimiento,
			cedula, ocupacion, telefono, telefono_emergencias)
			VALUES('$request->nombre', '$request->segundo_nombre', '$request->apellido', '$request->segundo_apellido', '$request->genero', '$request->fecha_nacimiento', '$request->cedula',
			'$request->ocupacion', '$request->telefono', '$request->telefono_emergencias');");
			return response()->json(['resultado' => 'exito']);
		}
	}
	
	public function registrarOdontologo(Request $request) {
		 $v = Validator::make($request->all(), [
        'nombre' => 'required|String',
		'segundo_nombre' => 'String',
		'apellido' => 'required|String',
		'segundo_apellido' => 'String',
        'cedula' => 'required|Integer',
		'especialidad' => 'required|String',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			DB::insert("INSERT INTO odontologo (nombre, segundo_nombre, apellido, segundo_apellido,
			cedula, especialidad)
			VALUES('$request->nombre', '$request->segundo_nombre', '$request->apellido', '$request->segundo_apellido', '$request->cedula',
			'$request->especialidad');");
			return response()->json(['resultado' => 'exito']);
		}
	}

	

	

}
