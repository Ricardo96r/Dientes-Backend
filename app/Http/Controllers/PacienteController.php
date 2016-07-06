<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\paciente;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class PacienteController extends Controller
{
    public function clientesPorEdad()
    {
		$paciente = paciente::select( DB::raw("DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento) AS edad, count(*) AS clientes"))
		->groupBY(DB::raw("DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento)"))
		->get();
		return $paciente;
    }
	
	public function historialPaciente($id_paciente) {
		$resultado= paciente::find($id_paciente)->historial;
        return $resultado;
	}
	
	public function datosPaciente($id_paciente) {
		$resultado= paciente::select( DB::raw("CONCAT(nombre,' ', segundo_nombre,' ', apellido,' ', segundo_apellido) AS nombre, fecha_nacimiento, cedula, ocupacion, telefono, telefono_emergencias, genero"))
		->where('paciente.id', '=', $id_paciente)
		->get();
        return $resultado;
	}
	
	public function pacientes() {
		$resultado= paciente::select(DB::raw("id, CONCAT(nombre, ' ', apellido) AS nombre"))
		->orderBy('nombre', 'asc')
		->get();

        return $resultado;
	}
	
	public function pacientesOdontologo($id_odontologo) {
		$resultado= paciente::select(DB::raw("paciente.id, CONCAT(nombre, ' ', apellido) AS nombre"))
		->join('cita', 'cita.id_paciente', '=', 'paciente.id')
		->where('cita.id_odontologo', '=', $id_odontologo)
		->orderBy('nombre', 'asc')
		->get();
		return $resultado;
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
			$paciente = new paciente;
			$paciente->nombre=$request->nombre;
			$paciente->segundo_nombre=$request->segundo_nombre;
			$paciente->apellido = $request->apellido;
			$paciente->segundo_apellido = $request->segundo_apellido;
			$paciente->genero=$request->genero;
			$paciente->fecha_nacimiento=$request->fecha_nacimiento;
			$paciente->cedula = $request->cedula;
			$paciente->ocupacion = $request->ocupacion;
			$paciente->telefono = $request->telefono;
			$paciente->telefono_emergencias = $request->telefono_emergencias;
			$paciente->save();
			return response()->json(['resultado' => 'exito', 'id_paciente'=> $paciente->id]);
		}
	}
	
}
