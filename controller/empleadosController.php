<?php
namespace App\controllers;

use App\models\entities\Empleado;

class EmpleadosController{
    function getAllEmpleados(){
        return Empleado::all();
    }
}
?>