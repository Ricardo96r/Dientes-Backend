<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Query extends Model
{
    /*
     *  Query 1
     *  Objetivo: Obtener la cantidad de clientes agrupados por edad
     */
    public static function clientesPorEdad()
    {
        return DB::select("
            SELECT (DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento)) AS edad, COUNT(*) AS clientes 
            FROM paciente 
            GROUP BY (DATEPART(year,getDate())-DATEPART(year,paciente.fecha_nacimiento));
        ");
    }

    /*
     *  Query 2
     *  Objetivo: Mostrar odontólogo con más pacientes de un mes, con sus datos y la cantidad de clientes atendidos.
     */
    public static function masPacientesMes($mes)
    {
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
}