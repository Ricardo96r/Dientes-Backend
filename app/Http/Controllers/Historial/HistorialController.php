<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use App\Query;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistorialController extends Controller
{
	 public function clientesPorEdad()
    {
        return DB::select("
            SELECT (DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento)) AS edad, COUNT(*) AS clientes 
            FROM paciente 
            GROUP BY (DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento));
        ");
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
	
	public function tratamientoMes($mes, $odontologo) {
        return DB::select("SELECT a.id_tratamiento, ROUND(SUM(a.costo), 0) AS costo
							FROM tratamiento a INNER JOIN consulta_has_tratamiento b ON
							a.id_tratamiento=b.id_tratamiento
							INNER JOIN consulta c ON b.id_consulta=c.id_consulta
							GROUP BY c.id_odontologo, DATEPART(YEAR,c.fecha), DATEPART(month,c.fecha),
							a.id_tratamiento
							HAVING c.id_odontologo='$odontologo' and DATEPART(month,c.fecha)='$mes';");
		}
	
	
	public function datosPaciente($id_paciente) {
        return DB::select("SELECT CONCAT(nombre,' ', segundo_nombre,' ', apellido,' ', segundo_apellido) AS nombre, fecha_nacimiento, cedula, ocupacion, telefono, telefono_emergencias, genero
		FROM paciente
		WHERE id_paciente='$id_paciente';");
	}
	
	public function historialPaciente($id_paciente) {
		//$resultado= DB::select("EXEC [dbo].[HistorialPaciente] @id_paciente='$id_paciente';");
		$resultado= DB::select("SELECT historial.*, CONCAT(paciente.nombre, ' ', paciente.apellido) as nombre FROM historial 
		INNER JOIN paciente
		on paciente.id_paciente = historial.id_paciente
		WHERE historial.id_paciente='$id_paciente';");
        return $resultado;
	}
	
	public function alergiasPaciente($id_paciente) {
		$resultado= DB::select("SELECT c.nombre, b.detalles
		FROM alergia_has_historial b
		INNER JOIN alergia c ON c.id_alergia=b.id_alergia
		WHERE b.id_paciente='$id_paciente';");
        return $resultado;
	}
	
	public function enfermedadesPaciente($id_paciente) {
		$resultado= DB::select("SELECT c.nombre, b.detalles
		FROM enfermedad_has_historial b
		INNER JOIN enfermedad c ON c.id_enfermedad=b.id_enfermedad
		WHERE b.id_paciente='$id_paciente';");
        return $resultado;
	}
	
	public function medicamentosPaciente($id_paciente) {
		$resultado= DB::select("SELECT c.nombre, b.detalles
		FROM medicamento_has_historial b INNER JOIN medicamento c ON
		c.id_medicamento=b.id_medicamento
		WHERE b.id_paciente='$id_paciente';");
        return $resultado;
	}
	
	public function dientesPaciente($id_paciente) {
		$resultado= DB::select("SELECT diente, seccion, observacion FROM historial_dientes
		WHERE id_paciente='$id_paciente';");
        return $resultado;
	}
	
	public function citasOdontologo($id_odontologo) {
		$resultado= DB::select("SELECT CONCAT(b.nombre,' ', b.apellido) AS paciente, a.motivo, a.fecha
		FROM cita a INNER JOIN paciente b ON a.id_paciente=b.id_paciente
		WHERE id_odontologo='$id_odontologo'
		AND DATEPART(year,a.fecha)=DATEPART(year,GETDATE())
		AND ((DATEPART(mm,a.fecha)=DATEPART(mm,GETDATE())
		AND DATEPART(dd,a.fecha)>DATEPART(dd,GETDATE()))OR DATEPART(mm,a.fecha)>DATEPART(mm,GETDATE()));");
        return $resultado;
	}
	
	public function citasDia() {
		$resultado= DB::select("SELECT CONCAT(c.nombre,' ', c.apellido) AS odontologo,
		CONCAT(b.nombre,' ', b.apellido) AS paciente, a.motivo,
		CONCAT(DATEPART(hh, a.fecha),':',DATEPART(mi, a.fecha)) AS hora
		FROM cita a INNER JOIN paciente b ON a.id_paciente=b.id_paciente
		INNER JOIN odontologo c ON a.id_odontologo=c.id_odontologo
		WHERE DATEPART(dd,a.fecha)=DATEPART(dd,GETDATE())
		AND DATEPART(mm,a.fecha)=DATEPART(mm,GETDATE())
		AND DATEPART(year,a.fecha)=DATEPART(year,GETDATE())
		ORDER BY hora;");
        return $resultado;
	}
	
	public function citasSemana() {
		$resultado= DB::select("SELECT CONCAT(c.nombre,' ', c.apellido) AS odontologo,
		CONCAT(b.nombre,' ', b.apellido) AS paciente,
		a.motivo, DATEPART(dw,a.fecha) AS dia,
		CONCAT(DATEPART(hh, a.fecha),':',DATEPART(mi, a.fecha)) AS hora
		FROM cita a INNER JOIN paciente b ON a.id_paciente=b.id_paciente
		INNER JOIN odontologo c ON a.id_odontologo=c.id_odontologo
		WHERE DATEPART(wk,a.fecha)=DATEPART(wk,GETDATE())
		AND DATEPART(mm,a.fecha)=DATEPART(mm,GETDATE())
		AND DATEPART(year,a.fecha)=DATEPART(year,GETDATE())
		ORDER BY dia, hora;");
        return $resultado;
	}
	
	public function citasMes() {
		$resultado= DB::select("SELECT CONCAT(c.nombre,' ', c.apellido) AS odontologo,
		CONCAT(b.nombre,' ', b.apellido) AS paciente,
		a.motivo, DATEPART(dd,a.fecha) AS dia,
		CONCAT(DATEPART(hh, a.fecha),':',DATEPART(mi, a.fecha)) AS hora
		FROM cita a INNER JOIN paciente b ON a.id_paciente=b.id_paciente
		INNER JOIN odontologo c ON a.id_odontologo=c.id_odontologo
		WHERE DATEPART(mm,a.fecha)=DATEPART(mm,GETDATE())
		AND DATEPART(year,a.fecha)=DATEPART(year,GETDATE())
		ORDER BY dia, hora;");
        return $resultado;
	}
	
	public function odontologos() {
		$resultado= DB::select("SELECT id_odontologo, CONCAT(nombre, ' ', apellido) AS nombre 
		FROM odontologo
		ORDER BY nombre;");
        return $resultado;
	}
	
	public function pacientes() {
		$resultado= DB::select("SELECT id_paciente, CONCAT(nombre, ' ', apellido) AS nombre 
		FROM paciente
		ORDER BY nombre;");
        return $resultado;
	}

	public function tratamientos() {
		$resultado= DB::select("SELECT id_tratamiento, nombre FROM tratamiento;");
        return $resultado;
	}
	
	public function registrarCita(Request $request) {
		 $v = Validator::make($request->all(), [
        'motivo' => 'required|string',
        'fecha' => 'required|date',
		'id_paciente' => 'required|exists:paciente,id_paciente',
        'id_odontologo' => 'required|exists:odontologo,id_odontologo',
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			DB::insert("INSERT INTO cita (motivo, fecha, id_paciente, id_odontologo)
			VALUES ('$request->motivo', '$request->fecha', '$request->id_paciente', '$request->id_odontologo');");
			return "Éxito";
		}
	}
	
	public function registrarConsulta(Request $request) {
		 $v = Validator::make($request->all(), [
        'observaciones' => 'required|string',
        'fecha' => 'required|date',
		'id_paciente' => 'required|exists:paciente,id_paciente',
        'id_odontologo' => 'required|exists:odontologo,id_odontologo',
		'id_tratamiento' => 'required|exists:tratamiento,id_tratamiento'
		]);

		if ($v->fails())
		{
			return $v->errors();
		}
		else{
			$id=DB::insert("INSERT INTO consulta (observaciones, fecha, id_paciente, id_odontologo)
			OUTPUT INSERTED.id_consulta
			VALUES ('$request->observaciones', '$request->fecha', '$request->id_paciente', '$request->id_odontologo');");
			
			DB::insert("INSERT INTO consulta_has_tratamiento VALUES('$id', '$request->id_tratamiento');");
			return "Éxito";
		}
	}
	
	public function consultasPaciente($id_paciente){
		$resultado=DB::select("SELECT a.*, c.*, CONCAT(d.nombre,' ',d.apellido) as odontologo
		FROM consulta a 
		LEFT JOIN consulta_has_tratamiento b 
		ON a.id_consulta=b.id_consulta 
		LEFT JOIN tratamiento c 
		ON c.id_tratamiento=b.id_tratamiento
		LEFT JOIN odontologo d
		ON d.id_odontologo=a.id_odontologo
		WHERE a.id_paciente='$id_paciente'");
		return $resultado;
	}
	
	public function consultasOdontologoPaciente($id_odontologo, $id_paciente) {
		return DB::select("
			SELECT a.*, c.*, CONCAT(d.nombre,' ',d.apellido) as odontologo
			FROM consulta a 
			LEFT JOIN consulta_has_tratamiento b 
			ON a.id_consulta=b.id_consulta 
			LEFT JOIN tratamiento c 
			ON c.id_tratamiento=b.id_tratamiento
			LEFT JOIN odontologo d
			ON d.id_odontologo=a.id_odontologo
			WHERE a.id_paciente='$id_paciente' AND a.id_odontologo='$id_odontologo'
		");
	}
	
	public function facturas(){
		$resultado=DB::select("SELECT id_consulta, fecha FROM factura");
		return $resultado;
	}
	
	public function factura($id_consulta){
		$resultado=DB::select("SELECT * FROM factura WHERE id_consulta='$id_consulta'");
		return $resultado;
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
			return "Éxito";
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
			return "Éxito";
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
			return "Éxito";
		}
	}

	public function pacientesOdontologo($id_odontologo) {
		$resultado= DB::select("SELECT paciente.id_paciente, CONCAT(nombre, ' ', apellido) AS nombre 
		FROM paciente
		INNER JOIN cita ON cita.id_paciente=paciente.id_paciente
		WHERE cita.id_odontologo='$id_odontologo'
		ORDER BY nombre;");
		return $resultado;
	}



}
