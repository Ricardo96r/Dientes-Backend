<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\paciente;

use Illuminate\Support\Facades\DB;

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


}
