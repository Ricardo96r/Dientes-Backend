<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $registros = 500;

        /*
         *  Paciente
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('paciente')->insert([
                'nombre' => $faker->firstName,
                'segundo_nombre' => $faker->randomElement([null, $faker->firstName]),
                'apellido' => $faker->lastName,
                'segundo_apellido' => $faker->randomElement([null, $faker->lastName]),
                'fecha_nacimiento' => $faker->dateTimeBetween('-80 years', '-5 years'),
                'cedula' => $faker->numberBetween('1000000', '32000000'),
                'ocupacion' => $faker->jobTitle,
                'telefono' => $faker->phoneNumber,
                'telefono_emergencias' => $faker->phoneNumber,
                'genero' => $faker->randomElement(['Hombre', 'Mujer']),
            ]);
        }

        /*
         * Odontologo
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('odontologo')->insert([
                'nombre' => $faker->firstName,
                'segundo_nombre' => $faker->randomElement([null, $faker->firstName]),
                'apellido' => $faker->lastName,
                'segundo_apellido' => $faker->randomElement([null, $faker->lastName]),
                'cedula' => $faker->numberBetween('1000000', '32000000'),
                'especialidad' => $faker->jobTitle,
            ]);
        }

        /*
         * Cita
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('cita')->insert([
                'motivo' => $faker->paragraph(),
                'fecha' => $faker->dateTimeBetween('-200 days', '30 days')->format('d-m-Y H:i:s'),
                'id_paciente' => $faker->numberBetween(1, DB::table('paciente')->count()),
                'id_odontologo' => $faker->numberBetween(1, DB::table('odontologo')->count()),
            ]);
        }

        /*
         * Consulta
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('consulta')->insert([
                'observaciones' => $faker->paragraph(),
                'fecha' => $faker->dateTimeBetween('-200 days', '30 days')->format('d-m-Y H:i:s'),
                'id_paciente' => $faker->numberBetween(1, DB::table('paciente')->count()),
                'id_odontologo' => $faker->numberBetween(1, DB::table('odontologo')->count()),
            ]);
        }

        /*
         * Factura
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('factura')->insert([
                'id_consulta' => $i + 1,
                'fecha' => $faker->dateTimeBetween('-200 days', '30 days')->format('d-m-Y H:i:s'),
                'costo' => $faker->randomFloat(2, 1000, 100000),
                'forma_de_pago' => $faker->randomElement(['Crédito', 'Débito', 'Efectivo', 'Cheque', 'Otro']),
            ]);
        }

        /*
         * Tratamiento
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('tratamiento')->insert([
                'nombre' => $faker->words(2, true),
                'detalles' => $faker->paragraph(),
                'costo' => $faker->randomFloat(2, 1000, 100000),
            ]);
        }

        /*
         * Consulta has Tratamiento
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('consulta_has_tratamiento')->insert([
                'id_consulta' => $i + 1,
                'id_tratamiento' => $faker->numberBetween(1, DB::table('tratamiento')->count()),
            ]);
        }

        /*
         * Historial
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('historial')->insert([
                'id_paciente' => $i + 1,
                'fecha_creacion' => $faker->dateTimeBetween('-200 days', '30 days')->format('d-m-Y H:i:s'),
                'ultima_visita_al_odontologo' => $faker->dateTimeBetween('-400 days', '-200 days')->format('d-m-Y H:i:s'),
                'aprieta_los_dientes' => $faker->boolean(),
                'dolor_de_dientes' => $faker->boolean(),
                'observacion_dolor' => $faker->randomElement([$faker->paragraph(), null]),
                'sangrado_de_encias' => $faker->boolean(),
                'observacion_sangrado' => $faker->randomElement([$faker->paragraph(), null]),
                'ruido_al_mover_la_mandibula' => $faker->boolean(),
                'observacion_ruidos' => $faker->randomElement([$faker->paragraph(), null]),
                'fuma' => $faker->boolean(),
                'cigaros_diarios' => $faker->randomElement([$faker->numberBetween(1, 30), null]),
                'muerde_objetos_extraños' => $faker->boolean(),
                'muerde_las_uñas' => $faker->boolean(),
                'experiencia_dental_negativa' => $faker->boolean(),
                'instruido_en_cepillado' => $faker->boolean(),
                'embarazo' => $faker->randomElement([null, true, false]),
                'ciclo_menstrual_regular' => $faker->randomElement([null, true, false]),
                'toma_anticonceptivos' => $faker->randomElement([null, true, false]),
            ]);
        }

        /*
         * Enfermedad
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('enfermedad')->insert([
                'nombre' => $faker->unique()->words(2, true),
            ]);
        }

        /*
         * Enfermedad has historial
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('enfermedad_has_historial')->insert([
                'id_enfermedad' => $faker->numberBetween(1, DB::table('enfermedad')->count()),
                'id_paciente' => $i + 1,
                'detalles' => $faker->randomElement([null, $faker->paragraph()]),
            ]);
        }

        /*
         * Medicamento
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('medicamento')->insert([
                'nombre' => $faker->unique()->words(2, true),
            ]);
        }

        /*
         * Medicamento has historial
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('medicamento_has_historial')->insert([
                'id_medicamento' => $faker->numberBetween(1, DB::table('medicamento')->count()),
                'id_paciente' => $i + 1,
                'detalles' => $faker->randomElement([null, $faker->paragraph()]),
            ]);
        }

        /*
         * Alergia
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('alergia')->insert([
                'nombre' => $faker->unique()->words(2, true),
            ]);
        }

        /*
         * Alergia has historial
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('alergia_has_historial')->insert([
                'id_alergia' => $faker->numberBetween(1, DB::table('alergia')->count()),
                'id_paciente' => $i + 1,
                'detalles' => $faker->randomElement([null, $faker->paragraph()]),
            ]);
        }

        /*
         * Historial dientes
         */
        for ($i = 0; $i < $registros; $i++) {
            DB::table('historial_dientes')->insert([
                'id_paciente' => $faker->numberBetween(1, DB::table('paciente')->count()),
                'diente' => $faker->numberBetween('11', '85'),
                'seccion' => $faker->numberBetween('1', '5'),
                'observacion' => $faker->paragraph(),
            ]);
        }

    }
}
