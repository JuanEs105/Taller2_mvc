<?php
namespace App\controllers;

use App\models\entities\Estado;

class EstadosController{
    function getAllEstados(){
        return Estado::all();
    }
}
?>