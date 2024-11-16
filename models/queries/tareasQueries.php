<?php

namespace App\models\queries;

class TareasQueries
{

    static function selectAll()
{
    return "
        SELECT 
            tareas.*, 
            prioridades.nombre AS Prioridad, 
            estados.nombre AS Estado, 
            empleados.nombre AS Empleado
        FROM 
            tareas
        LEFT JOIN 
            prioridades ON tareas.idPrioridad = prioridades.id
        LEFT JOIN 
            estados ON tareas.idEstado = estados.id
        LEFT JOIN 
            empleados ON tareas.idEmpleado = empleados.id
    ";
}
    
    
    static function insert($tarea)
    {
        $titulo = $tarea->get('titulo');
        $descripcion = $tarea->get('descripcion');
        $fechaEstimadaFinalizacion = $tarea->get('fechaEstimadaFinalizacion');
        $fechaFinalizacion = $tarea->get('fechaFinalizacion');
        $creadorTarea = $tarea->get('creadorTarea');
        $observaciones = $tarea->get('observaciones');
        $idEmpleado = $tarea->get('idEmpleado');
        $idEstado = $tarea->get('idEstado');
        $idPrioridad = $tarea->get('idPrioridad');
        $created_at = $tarea->get('created_at');
        $updated_at = $tarea->get('updated_at');
        $sql = "insert into tareas (titulo, descripcion, fechaEstimadaFinalizacion, fechaFinalizacion, creadorTarea, observaciones, idEmpleado, idEstado, idPrioridad, created_at, updated_at) values ";
        $sql .= "('$titulo','$descripcion', '$fechaEstimadaFinalizacion','$fechaFinalizacion','$creadorTarea','$observaciones','$idEmpleado','$idEstado','$idPrioridad','$created_at','$updated_at')";
        return $sql;
    }
    static function whereId($id)
    {
        return "select * from tareas where id=$id";
    }
    static function update($tarea)
    {
        $id = $tarea->get('id');
        $titulo = $tarea->get('titulo');
        $descripcion = $tarea->get('descripcion');
        $fechaEstimadaFinalizacion = $tarea->get('fechaEstimadaFinalizacion');
        $fechaFinalizacion = $tarea->get('fechaFinalizacion');
        $creadorTarea = $tarea->get('creadorTarea');
        $observaciones = $tarea->get('observaciones');
        $idEmpleado = $tarea->get('idEmpleado');
        $idEstado = $tarea->get('idEstado');
        $idPrioridad = $tarea->get('idPrioridad');
        $updated_at = $tarea->get('updated_at');
        $sql = "update tareas set titulo='$titulo', descripcion='$descripcion', fechaEstimadaFinalizacion='$fechaEstimadaFinalizacion', fechaFinalizacion='$fechaFinalizacion', creadorTarea='$creadorTarea', observaciones='$observaciones', idEmpleado='$idEmpleado', idEstado='$idEstado', idPrioridad='$idPrioridad', updated_at='$updated_at' where id=$id";
        return $sql;
    }

    static function delete($tarea)
    {
        $id = $tarea->get('id');
        return "delete from tareas where id=$id";
    }

    static function whereIdPrioridad($IdPrioridad)
    {
        return "select * from tareas where IdPrioridad=$IdPrioridad";
    }
    static function whereIdEstado($Id)
    {
        return "select * from tareas where IdEstado=$Id";
    }
    static function updateEstado($tarea)
    {
        $id = $tarea->get('id');
        $idEstado = $tarea->get('idEstado');
        $updated_at = $tarea->get('updated_at');
        $sql = "update tareas set idEstado='$idEstado', updated_at='$updated_at' where id=$id";
        return $sql;
    }
   
    static function updateEmpleado($tarea)
    {
        $id = $tarea->get('id');
        $idEmpleado = $tarea->get('idEmpleado');
        $updated_at = $tarea->get('updated_at');
        $sql = "update tareas set idEmpleado='$idEmpleado', updated_at='$updated_at' where id=$id";
        return $sql;
    }
    static function filtrerTarea($titulo, $fechaInicio, $fechaFin, $idPrioridad, $idEmpleado, $descripcion)
    {
        $query = "SELECT tareas.* FROM tareas
              INNER JOIN empleados ON tareas.idEmpleado = empleados.id
              INNER JOIN prioridades ON tareas.idPrioridad = prioridades.id
              WHERE 1=1";

        if (!empty($idPrioridad)) {
            $query .= " AND tareas.idPrioridad = '$idPrioridad'";
        }

        if (!empty($idEmpleado)) {
            $query .= " AND tareas.idEmpleado = '$idEmpleado'";
        }

        if (!empty($descripcion)) {
            $query .= " AND tareas.descripcion = '$descripcion'";
        }

        if (!empty($titulo)) {
            $query .= " AND tareas.titulo = '$titulo'";
        }

        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $query .= " AND tareas.fechaEstimadaFinalizacion BETWEEN '$fechaInicio' AND '$fechaFin'";
        } elseif (!empty($fechaInicio)) {
            $query .= " AND tareas.fechaEstimadaFinalizacion >= '$fechaInicio'";
        } elseif (!empty($fechaFin)) {
            $query .= " AND tareas.fechaEstimadaFinalizacion <= '$fechaFin'";
        }

        return $query;
    }
    static function agruparTarea($idEstado)
    {
        $query = "SELECT * FROM tareas WHERE 1=1";

        if (!empty($idEstado)) {
            $query .= " AND idEstado = '$idEstado'";
        }

        return $query;
    }
   
   

}