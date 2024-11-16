<?php
namespace App\controllers;

use App\models\entities\Prioridad;

class PrioridadesController{
    function getAllPrioridades(){
        return Prioridad::all();
    }
}
?>