<?php
/* Enable CORS */
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers:Origin, Content-Type, Accept, Authorization, X-Requested-With');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['middleware' => ['api'], 'prefix' => 'api/v1', 'namespace' => 'Historial'], function () {
    Route::get('clientesPorEdad', 'HistorialController@clientesPorEdad');
	Route::get('pacientesMes/{mes}', 'HistorialController@pacientesMes');
	Route::get('ingresosMes/{mes}', 'HistorialController@ingresosMes');
	Route::get('tratamientoMes/{mes}/{odontologo}', 'HistorialController@tratamientoMes');
	Route::get('paciente/{id_paciente}', 'HistorialController@datosPaciente');
	Route::get('historial/{id_paciente}', 'HistorialController@historialPaciente');
	Route::get('alergias/{id_paciente}', 'HistorialController@alergiasPaciente');
	Route::get('enfermedades/{id_paciente}', 'HistorialController@enfermedadesPaciente');
	Route::get('medicamentos/{id_paciente}', 'HistorialController@medicamentosPaciente');
	Route::get('dientes/{id_paciente}', 'HistorialController@dientesPaciente');
	Route::get('citas/{id_odontologo}', 'HistorialController@citasOdontologo');
	Route::get('citasDia', 'HistorialController@citasDia');
	Route::get('citasSemana', 'HistorialController@citasSemana');
	Route::get('citasMes', 'HistorialController@citasMes');
	Route::get('odontologos', 'HistorialController@odontologos');
	Route::get('pacientes', 'HistorialController@pacientes');
	Route::get('tratamientos', 'HistorialController@tratamientos');
	Route::get('consultasPaciente/{id_paciente}', 'HistorialController@consultasPaciente');
	Route::get('consulta/odontologo/{id_odontologo}/paciente/{id_paciente}', 'HistorialController@consultasOdontologoPaciente');
	Route::get('facturas/', 'HistorialController@facturas');
	Route::get('facturas/{id_consulta}', 'HistorialController@factura');
	Route::post('registrarCita', 'HistorialController@registrarCita');
	Route::post('registrarConsulta', 'HistorialController@registrarConsulta');
	Route::post('facturar', 'HistorialController@facturar');
	Route::post('registrarPaciente', 'HistorialController@registrarPaciente');
	Route::post('registrarOdontologo', 'HistorialController@registrarOdontologo');
	Route::get('odontologos/{id_odontologo}', 'HistorialController@pacientesOdontologo');
});


