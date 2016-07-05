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
	
	//PacienteController
    Route::get('clientesPorEdad', 'PacienteController@clientesPorEdad');
	Route::get('historial/{id_paciente}', 'PacienteController@historialPaciente');
	Route::get('paciente/{id_paciente}', 'PacienteController@datosPaciente');
	Route::get('pacientes', 'PacienteController@pacientes');
	Route::get('odontologos/{id_odontologo}', 'PacienteController@pacientesOdontologo');
	Route::post('registrarPaciente', 'PacienteController@registrarPaciente');
	
	//HistorialController
	Route::get('alergias/{id_paciente}', 'HistorialController@alergiasPaciente');
	Route::get('enfermedades/{id_paciente}', 'HistorialController@enfermedadesPaciente');
	Route::get('medicamentos/{id_paciente}', 'HistorialController@medicamentosPaciente');
	Route::get('dientes/{id_paciente}', 'HistorialController@dientesPaciente');
	
	//ConsultaController
	Route::get('consulta/{id_consulta}', 'ConsultaController@detallesConsulta');
	Route::get('consultasPaciente/{id_paciente}', 'ConsultaController@consultasPaciente');
	Route::get('consulta/odontologo/{id_odontologo}/paciente/{id_paciente}', 'ConsultaController@consultasOdontologoPaciente');
	Route::get('pacientesMes/{mes}', 'ConsultaController@pacientesMes');
	Route::get('ingresosMes/{mes}', 'ConsultaController@ingresosMes');
	Route::post('registrarConsulta', 'ConsultaController@registrarConsulta');
	
	//OdontologoController
	Route::get('odontologos', 'OdontologoController@odontologos');
	Route::get('detalleOdontologo/{id_odontologo}', 'OdontologoController@detallesOdontologo');
	Route::post('registrarOdontologo', 'odontologoController@registrarOdontologo');
	
	//TratamientoController
	Route::get('tratamientos', 'TratamientoController@tratamientos');
	Route::get('tratamientoMes/{mes}/{odontologo}', 'TratamientoController@tratamientoMes');
	
	//FacturaController
	Route::get('facturas', 'FacturaController@facturas');
	Route::get('facturas/{id_consulta}', 'FacturaController@factura');
	Route::post('facturar', 'FacturaController@facturar');
	
	//CitaController
	Route::get('citas/{id_odontologo}', 'CitaController@citasOdontologo');
	Route::get('citasPaciente/{id_paciente}', 'CitaController@citasPaciente');
	Route::get('citasDia', 'CitaController@citasDia');
	Route::get('citasMes', 'CitaController@citasMes');
	Route::get('detallesCita/{id_cita}','CitaController@cita');
	Route::post('registrarCita', 'CitaController@registrarCita');
	
	//AlergiaController
	Route::get('alergias','AlergiaController@alergias');
	
	//EnfermedadController
	Route::get('enfermedades','EnfermedadController@enfermedades');
	
	//MedicamentoController
	Route::get('medicamentos','MedicamentoController@medicamentos');

});


