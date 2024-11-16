<?php

namespace App\views;


use App\controller\TareasController;
use App\views\EstadosViews;
use App\views\EmpleadosViews;
use App\models\entities\Empleado;
use App\models\entities\Estado;
use App\models\entities\Prioridad;


class TareasView
{

    private $controller;
    function __construct()
    {
        $this->controller = new TareasController();
    }
    function getTable($titulo, $fechaInicio, $fechaFin, $idPrioridad, $idEmpleado, $descripcion, $idEstado)
{
    // Obtener datos necesarios
    $empleados = Empleado::all();
    $estados = Estado::all();
    $prioridades = Prioridad::all();

    // Obtener tareas filtradas
    $tareas = $this->controller->getAlltareas($titulo, $fechaInicio, $fechaFin, $idPrioridad, $idEmpleado, $descripcion, $idEstado);
    if (!is_array($tareas)) {
        $tareas = [$tareas];
    }

    // Ordenar tareas por prioridad y fecha estimada de finalización
    usort($tareas, fn($a, $b) => strcmp($a->get('idPrioridad'), $b->get('idPrioridad')) ?: strcmp($a->get('fechaEstimadaFinalizacion'), $b->get('fechaEstimadaFinalizacion')));

    // Construir filas de la tabla
    $rows = array_map(function ($tarea) use ($empleados, $estados, $prioridades) {
        $id = $tarea->get('id');
        $nombreEmpleado = $this->buscarNombre($empleados, $tarea->get('idEmpleado'), 'nombre');
        $nombreEstado = $this->buscarNombre($estados, $tarea->get('idEstado'), 'nombre');
        $nombrePrioridad = $this->buscarNombre($prioridades, $tarea->get('idPrioridad'), 'nombre');

        $estadoColor = $tarea->get('idEstado') == 4 ? 'style="color: red; font-weight: bold;"' : '';

        return '<tr>'
            . '<td>' . $tarea->get('titulo') . '</td>'
            . '<td>' . $tarea->get('descripcion') . '</td>'
            . '<td>' . $tarea->get('fechaEstimadaFinalizacion') . '</td>'
            . '<td>' . $tarea->get('fechaFinalizacion') . '</td>'
            . '<td>' . $tarea->get('creadorTarea') . '</td>'
            . '<td>' . $tarea->get('observaciones') . '</td>'
            . '<td>' . $nombreEmpleado . '</td>'
            . '<td ' . $estadoColor . '>' . $nombreEstado . '</td>'
            . '<td>' . $nombrePrioridad . '</td>'
            . '<td>' . $tarea->get('created_at') . '</td>'
            . '<td>' . $tarea->get('updated_at') . '</td>'
            . '<td><a href="formularioCrearTarea.php?cod=' . $id . '">Modificar</a></td>'
            .'<td><a href="eliminarTarea.php?cod='. $id .'" onclick="return confirm(\'¿Estás seguro de que deseas eliminar esta tarea?\');">Borrar</a></td>'
            . '<td><a href="formularioCrearTarea.php?cod=' . $id . '&campo=responsable">Responsable</a></td>'
            . '<td><a href="formularioCrearTarea.php=' . $id . '&campo=estado">Estado</a></td>'
            . '</tr>';
    }, $tareas);

    if (empty($rows)) {
        $rows[] = '<tr><td colspan="12">No hay datos registrados</td></tr>';
    }

    $table = '<table>'
        . '<thead>'
        . '<tr>'
        . '<th>Titulo</th>'
        . '<th>Descripcion</th>'
        . '<th>Fecha Estimada de Finalización</th>'
        . '<th>Fecha de Finalización</th>'
        . '<th>Creador</th>'
        . '<th>Observaciones</th>'
        . '<th>Empleado</th>'
        . '<th>Estado</th>'
        . '<th>Prioridad</th>'
        . '<th>Creado</th>'
        . '<th>Actualizado</th>'
        . '<th>Modificar</th>'
        . '<th>Borrar</th>'
        . '<th>Responsable</th>'
        . '<th>Estado</th>'
        . '</tr>'
        . '</thead>'
        . '<tbody>'
        . implode('', $rows)
        . '</tbody>'
        . '</table>';

    return $table;
}

private function buscarNombre($coleccion, $id, $campo)
{
    foreach ($coleccion as $item) {
        if ($item->id == $id) {
            return $item->$campo;
        }
    }
    return '';
}

    
function getFormTarea($data)
{
    $empleados = Empleado::all();
    $estados = Estado::all();
    $prioridades = Prioridad::all();

    $datos = !empty($data['cod']) ? $this->controller->getTarea($data['cod']) : null;
    $form = '<form action="confirmarTarea.php" method="post">';
    if ($datos) $form .= '<input type="hidden" name="cod" value="' . $data['cod'] . '">';

    $defaultValues = [
        'titulo' => '', 'descripcion' => '', 'fechaEstimadaFinalizacion' => '', 
        'fechaFinalizacion' => '', 'creadorTarea' => '', 'observaciones' => '', 
        'idEmpleado' => '', 'idEstado' => '', 'idPrioridad' => ''
    ];
    foreach ($defaultValues as $key => $default) {
        $$key = $datos ? $datos->get($key) : $default;
    }

    $campo = $data['campo'] ?? '';

    $hiddenFields = function () use ($titulo, $descripcion, $fechaEstimadaFinalizacion, $fechaFinalizacion, $creadorTarea, $observaciones) {
        return <<<HTML
            <input type="hidden" name="titulo" value="$titulo">
            <textarea name="descripcion" style="display: none;">$descripcion</textarea>
            <input type="hidden" name="fechaEstimadaFinalizacion" value="$fechaEstimadaFinalizacion">
            <input type="hidden" name="fechaFinalizacion" value="$fechaFinalizacion">
            <input type="hidden" name="creadorTarea" value="$creadorTarea">
            <textarea name="observaciones" style="display: none;">$observaciones</textarea>
        HTML;
    };

    $selectField = function ($name, $options, $selectedValue, $style = '') {
        $html = "<select name=\"$name\" required $style>";
        $html .= '<option value="">Selecciona una opción</option>';
        foreach ($options as $option) {
            $selected = ($option->id == $selectedValue) ? 'selected' : '';
            $html .= "<option value=\"$option->id\" $selected>$option->nombre</option>";
        }
        return $html . '</select>';
    };

    if ($campo === 'responsable') {
        $form .= $hiddenFields();
        $form .= '<label for="idEmpleado">Ingrese el empleado a cargo</label>';
        $form .= $selectField('idEmpleado', $empleados, $idEmpleado);
        $form .= $selectField('idEstado', $estados, $idEstado, 'style="display: none;"');
        $form .= $selectField('idPrioridad', $prioridades, $idPrioridad, 'style="display: none;"');
    } elseif ($campo === 'estado') {
        $form .= $hiddenFields();
        $form .= $selectField('idEmpleado', $empleados, $idEmpleado, 'style="display: none;"');
        $form .= '<label for="idEstado">Ingrese el estado actual de la tarea</label>';
        $form .= $selectField('idEstado', $estados, $idEstado);
        $form .= $selectField('idPrioridad', $prioridades, $idPrioridad, 'style="display: none;"');
    } else {
        $form .= <<<HTML
            <label for="titulo">Ingrese el título</label>
            <input type="text" name="titulo" placeholder="Título" value="$titulo" required>
            <label for="descripcion">Ingrese la descripción</label>
            <textarea name="descripcion" placeholder="Descripción">$descripcion</textarea>
            <label for="fechaEstimadaFinalizacion">Fecha estimada de finalización</label>
            <input type="date" name="fechaEstimadaFinalizacion" value="$fechaEstimadaFinalizacion">
            <label for="fechaFinalizacion">Fecha de finalización</label>
            <input type="date" name="fechaFinalizacion" value="$fechaFinalizacion">
            <label for="creadorTarea">Creador</label>
            <input type="text" name="creadorTarea" placeholder="Creador" value="$creadorTarea">
            <label for="observaciones">Observaciones</label>
            <textarea name="observaciones" placeholder="Observaciones">$observaciones</textarea>
            <label for="idEmpleado">Empleado a cargo</label>
        HTML;
        $form .= $selectField('idEmpleado', $empleados, $idEmpleado);
        $form .= '<label for="idEstado">Estado actual</label>';
        $form .= $selectField('idEstado', $estados, $idEstado);
        $form .= '<label for="idPrioridad">Prioridad</label>';
        $form .= $selectField('idPrioridad', $prioridades, $idPrioridad);
    }

    $form .= '<button type="submit">Guardar Tarea</button></form>';
    return $form;
}



    function getMsgDeleteTarea($id){
        $confirmarAccion = $this->controller->deleteTarea($id);
        $msg = '<h2>Resultado de la operación</h2>';
        if ($confirmarAccion) {
            $msg .= '<p>tarea eliminada.</p>';
        } else {
            $msg .= '<p>No se pudo eliminar la información de la tarea</p>';
        }
        return $msg;
    }
    
    function getMsgNewEstado($datosestado)
{
    $datos = [
        "id" => $datosestado['cod'],
        "idEstado" => $datosestado['estado']
    ];
    $confirmarAccion = $this->controller->updateEstado($datos);
    $msg = '<h2>Resultado de la operación</h2>';
    if (isset($datosestado['created_at'])) {
        $datos['created_at'] = $datosestado['created_at'];
    }
    if ($confirmarAccion) {
        $msg .= '<p class="msgExito">Estado modificado correctamente.</p>';
    } else {
        $msg .= '<p class="msgError">No se pudo guardar la información del estado</p>';
    }
    return $msg;
}


function getMsgNewEmpleado($datosempleado)
{
    $datos = [
        "id" => $datosempleado['cod'],
        "idEmpleado" => $datosempleado['empleado']
    ];
    $confirmarAccion = $this->controller->updateEmpleado($datos);
    $msg = '<h2>Resultado de la operación</h2>';
    if (isset($datosempleado['created_at'])) {
        $datos['created_at'] = $datosempleado['created_at'];
    }
    if ($confirmarAccion) {
        $msg .= '<p class="msgExito">Empleado modificado correctamente.</p>';
    } else {
        $msg .= '<p class="msgError">No se pudo guardar la información del empleado</p>';
    }
    return $msg;
}
function getMsgUpdateTarea($datosFormulario)
    {
        $datos = [
            'id' => $datosFormulario['cod'],
            "titulo" => $datosFormulario['titulo'],
            "descripcion" => $datosFormulario['descripcion'],
            "fechaEstimadaFinalizacion" => $datosFormulario['fechaEstimadaFinalizacion'],
            "fechaFinalizacion" => $datosFormulario['fechaFinalizacion'],
            "creadorTarea" => $datosFormulario['creadorTarea'],
            "observaciones" => $datosFormulario['observaciones'],
            "idEmpleado" => $datosFormulario['idEmpleado'],
            "idEstado" => $datosFormulario['idEstado'],
            "idPrioridad" => $datosFormulario['idPrioridad'],
        ];
        $confirmarAccion = $this->controller->updateTarea($datos);
        $msg = '<h2>Resultado de la operación</h2>';
        if ($confirmarAccion) {
            $msg .= '<p>Datos del contacto guardados.</p>';
        } else {
            $msg .= '<p>No se pudo guardar la información del contacto</p>';
        }
        return $msg;
    }
    function getMsgNewTarea($datosFormulario)
    {
        $datos = [
            "titulo" => $datosFormulario['titulo'],
            "descripcion" => $datosFormulario['descripcion'],
            "fechaEstimadaFinalizacion" => $datosFormulario['fechaEstimadaFinalizacion'],
            "fechaFinalizacion" => $datosFormulario['fechaFinalizacion'],
            "creadorTarea" => $datosFormulario['creadorTarea'],
            "observaciones" => $datosFormulario['observaciones'],
            "idEmpleado" => $datosFormulario['idEmpleado'],
            "idEstado" => $datosFormulario['idEstado'],
            "idPrioridad" => $datosFormulario['idPrioridad'],
        ];
        $confirmarAccion = $this->controller->saveTarea($datos);
        $msg = '<h2>Resultado de la operación</h2>';
        if ($confirmarAccion) {
            $msg .= '<p>La tarea se guardo correctamente.</p>';
        } else {
            $msg .= '<p>No se pudo guardar la tarea</p>';
        }
        return $msg;
    }

    function getFormTareaModificar()
    {
        $datos = $this->controller->getTarea($_GET['cod']);
        $titulo = empty($datos) ?: $datos->get('titulo');
        $descripcion = empty($datos) ?:  $datos->get('descripcion');
        $fechaEstimadaFinalizacion = empty($datos) ?:  $datos->get('fechaEstimadaFinalizacion');
        $fechaFinalizacion = empty($datos) ?:  $datos->get('fechaFinalizacion');
        $creadorTarea = empty($datos) ?:  $datos->get('creadorTarea');
        $observaciones = empty($datos) ?:  $datos->get('observaciones');
        $idEmpleado = empty($datos) ?:  $datos->get('idEmpleado');
        $idEstado = empty($datos) ?:  $datos->get('idEstado');
        $idPrioridad = empty($datos) ?:  $datos->get('idPrioridad');

        $form = '<form action="confirmarTarea.php" method="post">';
        $form .= '      <input type="text" name="titulo" placeholder="Título" value="' . $titulo . '" required>';
        $form .= '      <textarea name="descripcion" placeholder="Descripción" value="' . $descripcion . '" ></textarea>';
        $form .= '      <input type="date" name="fechaEstimadaFinalizacion" value="' . $fechaEstimadaFinalizacion . '" >';
        $form .= '      <input type="date" name="fechaFinalizacion" value="' . $fechaFinalizacion . '" >';
        $form .= '      <input type="text" name="creadorTarea" placeholder="Creador" value="' . $creadorTarea . '" >';
        $form .= '      <textarea name="observaciones" placeholder="Observaciones" value="' . $observaciones . '" ></textarea>';
        $form .= '      <input type="number" name="idEmpleado" placeholder="ID Empleado"value="' . $idEmpleado . '" required>';
        $form .= '      <input type="number" name="idEstado" placeholder="ID Estado" value="' . $idEstado . '" required>';
        $form .= '      <input type="number" name="idPrioridad" placeholder="ID Prioridad" value="' . $idPrioridad . '" required>';
        $form .= '      <button type="submit">Guardar Tarea</button>';
        return $form;
    }



}