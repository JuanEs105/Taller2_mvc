<?php
namespace App\controller;
use App\models\entities\Tarea;

class TareasController {


    function getAllTareas($filtro) {
        return Tarea::all();
    }
    
    function saveTarea($datos) {
        $tarea = new Tarea();
        $tarea->set('titulo', $datos['titulo']);
        $tarea->set('descripcion', $datos['descripcion']);
        $tarea->set('fechaEstimadaFinalizacion', $datos['fechaEstimadaFinalizacion']);
        $tarea->set('fechaFinalizacion', $datos['fechaFinalizacion']);
        $tarea->set('creadorTarea', $datos['creadorTarea']);
        $tarea->set('observaciones', $datos['observaciones']);
        $tarea->set('idEmpleado', $datos['idEmpleado']);
        $tarea->set('idEstado', $datos['idEstado']);
        $tarea->set('idPrioridad', $datos['idPrioridad']);
        $tarea->set('created_at', date("Y-m-d H:i:s"));
        $tarea->set('updated_at', date("Y-m-d H:i:s"));
        return $tarea->save();
    }
    function getTarea($id) {
        return Tarea::find($id);
    }
    function updateTarea($datos) {
        $tarea = new Tarea();
        $tarea->set('id', $datos['id']);
        $tarea->set('titulo', $datos['titulo']);
        $tarea->set('descripcion', $datos['descripcion']);
        $tarea->set('fechaEstimadaFinalizacion', $datos['fechaEstimadaFinalizacion']);
        $tarea->set('fechaFinalizacion', $datos['fechaFinalizacion']);
        $tarea->set('creadorTarea', $datos['creadorTarea']);
        $tarea->set('observaciones', $datos['observaciones']);
        $tarea->set('idEmpleado', $datos['idEmpleado']);
        $tarea->set('idEstado', $datos['idEstado']);
        $tarea->set('idPrioridad', $datos['idPrioridad']);
        $tarea->set('updated_at',  date("Y-m-d H:i:s"));
        return $tarea->update();
    }
    function deleteTarea($id)
    {
        $tarea = new Tarea();
        $tarea->set('id', $id);
        return $tarea->delete();
    }
    function updateEstado($datos) {
        $tarea = new Tarea();
        $tarea->set('id', $datos['id']);
        $tarea->set('idEstado', $datos['idEstado']);
        return $tarea->updateEstado();
    }
    function updateEmpleado($datos) {
        $tarea = new Tarea();
        $tarea->set('id', $datos['id']);
        $tarea->set('idEmpleado', $datos['idEmpleado']);
        return $tarea->updateEmpleado();
    }
}
?>