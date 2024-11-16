<?php
namespace App\models\entities;

use App\models\db\tareasdb;
use App\models\queries\EstadosQueries;

class Estado{
    function set($prop, $value)
    {
        $this->{$prop} = $value;
    }

    function get($prop)
    {
        return $this->{$prop};
    }

    static function all(){
        $sql = EstadosQueries::selectAll();
        $db = new tareasdb();
        $result = $db->query($sql);
        $estados = [];
        while ($row = $result->fetch_assoc()) {
            $estado = new Estado();
            $estado->set('id', $row['id']);
            $estado->set('nombre', $row['nombre']);
            array_push($estados, $estado);
        }
        $db->close();
        return $estados;
    }
}
?>