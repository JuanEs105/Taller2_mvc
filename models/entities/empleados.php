<?php
namespace App\models\entities;

use App\models\db\tareasdb;
use App\models\queries\EmpleadosQueries;

class Empleado {
    function set($prop, $value) {
        $this->{$prop} = $value;
    }

    function get($prop) {
        return $this->{$prop};
    }

    static function all() {
        $sql = EmpleadosQueries::selectAll();
        $db = new tareasdb();
        $result = $db->query($sql);
        $empleados = [];
        
        while ($row = $result->fetch_assoc()) {
            $empleado = new Empleado();
            $empleado->set('id', $row['id']);
            $empleado->set('nombre', $row['nombre']);
            array_push($empleados, $empleado);
        }
        
        $db->close();
        return $empleados;
    }
}
?>
