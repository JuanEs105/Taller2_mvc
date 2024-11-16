<?php
namespace App\models\entities;

use App\models\db\tareasdb;
use App\models\queries\PrioridadesQueries;

class Prioridad {
    function set($prop, $value) {
        $this->{$prop} = $value;
    }

    function get($prop) {
        return $this->{$prop};
    }

    static function all() {
        $sql = PrioridadesQueries::selectAll();
        $db = new tareasdb();
        $result = $db->query($sql);
        $prioridades = [];
        
        while ($row = $result->fetch_assoc()) {
            $prioridad = new Prioridad();
            $prioridad->set('id', $row['id']);
            $prioridad->set('nombre', $row['nombre']);
            array_push($prioridades, $prioridad);
        }
        
        $db->close();
        return $prioridades;
    }
}
?>
