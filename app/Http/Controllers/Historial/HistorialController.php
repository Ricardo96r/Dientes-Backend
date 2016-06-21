<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use App\Query;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;

class HistorialController extends Controller
{
    public function historial() {
        return Query::odontologoTopMes(2); // Ejemplo
    }
}
