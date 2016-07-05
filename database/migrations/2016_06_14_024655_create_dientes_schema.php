<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDientesSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {
            Schema::create('paciente', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->string('nombre');
                $tabla->string('segundo_nombre')->nullable();
                $tabla->string('apellido');
                $tabla->string('segundo_apellido')->nullable();
                $tabla->date('fecha_nacimiento');
                $tabla->integer('cedula');
                $tabla->string('ocupacion');
                $tabla->string('telefono');
                $tabla->string('telefono_emergencias');
                $tabla->string('genero');
            });

            Schema::create('odontologo', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->string('nombre');
                $tabla->string('segundo_nombre')->nullable();
                $tabla->string('apellido');
                $tabla->string('segundo_apellido')->nullable();
                $tabla->integer('cedula');
                $tabla->string('especialidad');
            });

            Schema::create('cita', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->text('motivo');
                $tabla->dateTime('fecha');
                $tabla->integer('id_paciente')->unsigned();
                $tabla->integer('id_odontologo')->unsigned();

                $tabla->foreign('id_odontologo')->references('id')->on('odontologo')->onDelete('cascade');
                $tabla->foreign('id_paciente')->references('id')->on('paciente')->onDelete('cascade');
            });

            Schema::create('consulta', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->text('observaciones');
                $tabla->date('fecha');
                $tabla->integer('id_paciente')->unsigned();
                $tabla->integer('id_odontologo')->unsigned();

                $tabla->foreign('id_odontologo')->references('id')->on('odontologo')->onDelete('cascade');
                $tabla->foreign('id_paciente')->references('id')->on('paciente')->onDelete('cascade');
            });

            Schema::create('factura', function (Blueprint $tabla) {
                $tabla->integer('id')->primary()->unsigned();
                $tabla->dateTime('fecha');
                $tabla->float('costo');
                $tabla->string('forma_de_pago');

                $tabla->foreign('id')->references('id')->on('consulta')->onDelete('cascade');
            });

            Schema::create('tratamiento', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->string('nombre');
                $tabla->text('detalles');
                $tabla->float('costo');
            });

            Schema::create('consulta_has_tratamiento', function (Blueprint $tabla) {
                $tabla->integer('id_consulta')->unsigned();
                $tabla->integer('id_tratamiento')->unsigned();

                $tabla->foreign('id_consulta')->references('id')->on('consulta')->onDelete('cascade');
                $tabla->foreign('id_tratamiento')->references('id')->on('tratamiento')->onDelete('cascade');
                $tabla->primary(['id_consulta', 'id_tratamiento']);
            });

            Schema::create('historial', function (Blueprint $tabla) {
                $tabla->integer('id')->primary()->unsigned();
                $tabla->dateTime('fecha_creacion')->default(DB::raw('GETDATE()'));
                $tabla->date('ultima_visita_al_odontologo');
                $tabla->boolean('aprieta_los_dientes');
                $tabla->boolean('dolor_de_dientes');
                $tabla->text('observacion_dolor')->nullable();
                $tabla->boolean('sangrado_de_encias');
                $tabla->text('observacion_sangrado')->nullable();
                $tabla->boolean('ruido_al_mover_la_mandibula');
                $tabla->text('observacion_ruidos')->nullable();
                $tabla->boolean('fuma');
                $tabla->integer('cigaros_diarios')->nullable();
                $tabla->boolean('muerde_objetos_extranos');
                $tabla->boolean('muerde_las_unas');
                $tabla->boolean('experiencia_dental_negativa');
                $tabla->boolean('instruido_en_cepillado');
                $tabla->boolean('embarazo')->nullable();
                $tabla->boolean('ciclo_menstrual_regular')->nullable();
                $tabla->boolean('toma_anticonceptivos')->nullable();

                $tabla->foreign('id')->references('id')->on('paciente')->onDelete('cascade');
            });

            Schema::create('historial_dientes', function (Blueprint $tabla) {
				$tabla->increments('id');
                $tabla->integer('id_paciente')->unsigned();
                $tabla->integer('diente');
                $tabla->integer('seccion');
                $tabla->text('observacion');

				$tabla->unique(['id_paciente', 'diente', 'seccion']);
                $tabla->foreign('id_paciente')->references('id')->on('paciente')->onDelete('cascade');
            });

            Schema::create('enfermedad', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->string('nombre');
            });

            Schema::create('enfermedad_has_historial', function (Blueprint $tabla) {
                $tabla->integer('id_enfermedad')->unsigned();
                $tabla->integer('id_paciente')->unsigned();
                $tabla->text('detalles')->nullable();

                $tabla->foreign('id_enfermedad')->references('id')->on('enfermedad')->onDelete('cascade');
                $tabla->foreign('id_paciente')->references('id')->on('paciente')->onDelete('cascade');
                $tabla->primary(['id_enfermedad', 'id_paciente']);
            });

            Schema::create('medicamento', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->string('nombre');
            });
            
            Schema::create('medicamento_has_historial', function (Blueprint $tabla) {
                $tabla->integer('id_medicamento')->unsigned();
                $tabla->integer('id_paciente')->unsigned();
                $tabla->text('detalles')->nullable();

                $tabla->foreign('id_medicamento')->references('id')->on('medicamento')->onDelete('cascade');
                $tabla->foreign('id_paciente')->references('id')->on('paciente')->onDelete('cascade');
                $tabla->primary(['id_medicamento', 'id_paciente']);
            });
            
            Schema::create('alergia', function (Blueprint $tabla) {
                $tabla->increments('id');
                $tabla->string('nombre');
            });

            Schema::create('alergia_has_historial', function (Blueprint $tabla) {
                $tabla->integer('id_alergia')->unsigned();
                $tabla->integer('id_paciente')->unsigned();
                $tabla->text('detalles')->nullable();

                $tabla->foreign('id_alergia')->references('id')->on('alergia')->onDelete('cascade');
                $tabla->foreign('id_paciente')->references('id')->on('paciente')->onDelete('cascade');
                $tabla->primary(['id_alergia', 'id_paciente']);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alergia_has_historial');
        Schema::drop('alergia');
        Schema::drop('medicamento_has_historial');
        Schema::drop('medicamento');
        Schema::drop('enfermedad_has_historial');
        Schema::drop('enfermedad');
        Schema::drop('historial_dientes');
        Schema::drop('historial');
        Schema::drop('consulta_has_tratamiento');
        Schema::drop('tratamiento');
        Schema::drop('factura');
        Schema::drop('consulta');
        Schema::drop('cita');
        Schema::drop('odontologo');
        Schema::drop('paciente');
    }
}
