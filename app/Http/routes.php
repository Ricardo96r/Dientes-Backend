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

Route::group(['middleware' => ['api'], 'prefix' => 'api/v1', 'namespace'], function () {
    Route::get('clientesPorEdad', 'PacienteController@clientesPorEdad');
	Route::get('pacientesMes/{mes}', 'ViejoController@pacientesMes');
	Route::get('ingresosMes/{mes}', 'ViejoController@ingresosMes');
	Route::get('tratamientoMes/{mes}/{odontologo}', 'ViejoController@tratamientoMes');
	Route::get('paciente/{id_paciente}', 'ViejoController@datosPaciente');
	Route::get('historial/{id_paciente}', 'ViejoController@historialPaciente');
	Route::get('alergias/{id_paciente}', 'ViejoController@alergiasPaciente');
	Route::get('enfermedades/{id_paciente}', 'ViejoController@enfermedadesPaciente');
	Route::get('medicamentos/{id_paciente}', 'ViejoController@medicamentosPaciente');
	Route::get('dientes/{id_paciente}', 'ViejoController@dientesPaciente');
	Route::get('citas/{id_odontologo}', 'ViejoController@citasOdontologo');
	Route::get('citasDia', 'ViejoController@citasDia');
	Route::get('citasSemana', 'ViejoController@citasSemana');
	Route::get('citasMes', 'ViejoController@citasMes');
	Route::get('odontologos', 'ViejoController@odontologos');
	Route::get('pacientes', 'ViejoController@pacientes');
	Route::get('tratamientos', 'ViejoController@tratamientos');
	Route::get('consultasPaciente/{id_paciente}', 'ViejoController@consultasPaciente');
	Route::get('consulta/odontologo/{id_odontologo}/paciente/{id_paciente}', 'ViejoController@consultasOdontologoPaciente');
	Route::get('facturas/', 'ViejoController@facturas');
	Route::get('facturas/{id_consulta}', 'ViejoController@factura');
	Route::post('registrarCita', 'ViejoController@registrarCita');
	Route::post('registrarConsulta', 'ViejoController@registrarConsulta');
	Route::post('facturar', 'ViejoController@facturar');
	Route::post('registrarPaciente', 'ViejoController@registrarPaciente');
	Route::post('registrarOdontologo', 'ViejoController@registrarOdontologo');
	Route::get('odontologos/{id_odontologo}', 'ViejoController@pacientesOdontologo');
	Route::get('detalleOdontologo/{id_odontologo}', 'ViejoController@detallesOdontologo');
});


