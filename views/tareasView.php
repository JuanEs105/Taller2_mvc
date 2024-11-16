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
    function getTable($filtro=null)
    {

        $rows = '';
        $tareas = $this->controller->getAllTareas($filtro);

        
        if (count($tareas ) > 0) {
            foreach ($tareas  as $tareas) {
                $id = $tareas ->get('id');
                $rows .= '<tr>';
                $rows .= '   <td>' . $tareas ->get('titulo') . '</td>';
                $rows .= '   <td>' . $tareas ->get('descripcion') . '</td>';
                $rows .= '   <td>' . $tareas ->get('fechaEstimadaFinalizacion') . '</td>';
                $rows .= '   <td>' . $tareas ->get('fechaFinalizacion') . '</td>';
                $rows .= '   <td>' . $tareas ->get('creadorTarea') . '</td>';
                $rows .= '   <td>' . $tareas ->get('observaciones') . '</td>';
                $rows .= '   <td>' . $tareas ->get('empleado')->get('nombre') . '</td>';
                $rows .= '   <td>';
                $rows .= '     <a class="boton" href="modificarEmpleado.php?cod=' . $id . '">Reasignar responsable</a>';
                $rows .= '   </td>';
                $estadoNombre = $tareas->get('estado')->get('nombre');
                if ($estadoNombre == "En impedimento") {
                    $rows .= '   <td class="impedimento">'.$estadoNombre.'</td>';
                } else {
                    $rows .= '   <td>'.$estadoNombre.'</td>';
                }
                $rows .= '   <td>';
                $rows .= '     <a class="boton" href="modificarEstado.php?cod=' . $id . '">Estado</a>';
                $rows .= '   </td>';
                $rows .= '<form action="" method="get">';
                $rows .= '</form>';
                $rows .= '   </td>';
                $rows .= '   <td>' . $tareas ->get('prioridad')->get('nombre') . '</td>';
                 $rows .= '   <td>' . $tareas ->get('created_at') . '</td>';
                $rows .= '   <td>' . $tareas ->get('updated_at') . '</td>';
                $rows .= '   </td>';
                $rows .= '   <td>';
                $rows .= '      <a id=modificar href="formulariosTareas.php?cod=' . $id . '">Modificar</a>';
                $rows .= '   </td>';
                $rows .= '   <td>';
                $rows .= '     <a id=eliminar href="eliminarTarea.php?cod=' . $id . '">Eliminar</a>';
                $rows .= '   </td>';  
                $rows .= '</tr>';
            }
        } else {
            $rows .= '<tr>';
            $rows .= '   <td colspan="3">No hay datos registrados</td>';
            $rows .= '</tr>';
        } 
        $rows .= '        <h1><a id=crear href="formulariosTareas.php">Crear</a></h1>';
        $table = '<table class="tabla">';
        $table .= '  <thead>';
        $table .= '    <tr>'; 
        $table .= '         <th>Título</th>';
        $table .= '         <th>Descripción</th>';
        $table .= '         <th>fecha estimada finalizacion</th>';
        $table .= '         <th>Fecha de finalizacion</th>';
        $table .= '         <th>Creador de la tarea</th>';
        $table .= '         <th>Observaciones</th>';
        $table .= '         <th>Empleado</th>';
        $table .= '         <th>Reasignar Empleado</th>';
        $table .= '         <th>Estado</th>';
        $table .= '         <th>Cambiar Estado</th>';
        $table .= '         <th>Prioridad</th>';
         $table .= '         <th>Fcha de Creación</th>';
        $table .= '         <th>Actualizado</th>';
       
        $table .= '     </tr>'; 
        $table .= '  </thead>';
        $table .= ' <tbody>';
        $table .=  $rows;  
        $table .= ' </tbody>';
        $table .= '</table>';
        return $table;

    } 
    
    function getFormTarea($data)
{
    $empleados = Empleado::all();
    $estados = Estado::all();
    $prioridades = Prioridad::all();

    $datos = null;
    $form = '<form action="confirmarTarea.php" method="post">';

    // Si hay un código de tarea, se busca la tarea específica
    if (!empty($data['cod'])) {
        $form .= '<input type="hidden" name="cod" value="' . $data['cod'] . '">';
        $datos = $this->controller->getTarea($data['cod']);
    }

    // Asignación de valores por defecto
    $campo = isset($data['campo']) ? $data['campo'] : '';
    $idEmpleado = $datos ? $datos->get('idEmpleado') : '';
    $idEstado = $datos ? $datos->get('idEstado') : '';
    $idPrioridad = $datos ? $datos->get('idPrioridad') : '';
    $titulo = $datos ? $datos->get('titulo') : '';
    $descripcion = $datos ? $datos->get('descripcion') : '';
    $fechaEstimadaFinalizacion = $datos ? $datos->get('fechaEstimadaFinalizacion') : '';
    $fechaFinalizacion = $datos ? $datos->get('fechaFinalizacion') : '';
    $creadorTarea = $datos ? $datos->get('creadorTarea') : '';
    $observaciones = $datos ? $datos->get('observaciones') : '';

    // Elementos comunes a todos los formularios
    $hiddenFields = [
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'fechaEstimadaFinalizacion' => $fechaEstimadaFinalizacion,
        'fechaFinalizacion' => $fechaFinalizacion,
        'creadorTarea' => $creadorTarea,
        'observaciones' => $observaciones
    ];

    foreach ($hiddenFields as $name => $value) {
        $form .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';
    }

    // Función para generar los selectores de empleados, estados y prioridades
    function generateSelect($name, $items, $selectedId)
    {
        $options = '<option value="">Selecciona un ' . $name . '</option>';
        foreach ($items as $item) {
            $selected = ($item->id == $selectedId) ? 'selected' : '';
            $options .= '<option value="' . $item->id . '" ' . $selected . '>' . $item->nombre . '</option>';
        }
        return '<label for="id' . ucfirst($name) . '">Ingrese el ' . $name . ' de la tarea</label>' .
               '<select name="id' . ucfirst($name) . '" required>' . $options . '</select><br>';
    }

    if ($campo === 'responsable') {
        $form .= generateSelect('Empleado', $empleados, $idEmpleado);
        $form .= generateSelect('Estado', $estados, $idEstado);
        $form .= generateSelect('Prioridad', $prioridades, $idPrioridad);
    } elseif ($campo === 'estado') {
        $form .= generateSelect('Empleado', $empleados, $idEmpleado);
        $form .= generateSelect('Estado', $estados, $idEstado);
        $form .= generateSelect('Prioridad', $prioridades, $idPrioridad);
    } else {
        $form .= '<label for="titulo">Ingrese el título</label>';
        $form .= '<input type="text" name="titulo" placeholder="Título" value="' . $titulo . '" required><br>';

        $form .= '<label for="descripcion">Ingrese la descripción</label>';
        $form .= '<textarea name="descripcion" placeholder="Descripción">' . $descripcion . '</textarea><br>';

        $form .= '<label for="fechaEstimadaFinalizacion">Ingrese la fecha estimada de finalización</label>';
        $form .= '<input type="date" name="fechaEstimadaFinalizacion" value="' . $fechaEstimadaFinalizacion . '"><br>';

        $form .= '<label for="fechaFinalizacion">Ingrese la fecha de finalización</label>';
        $form .= '<input type="date" name="fechaFinalizacion" value="' . $fechaFinalizacion . '"><br>';

        $form .= '<label for="creadorTarea">Ingrese el creador de la tarea</label>';
        $form .= '<input type="text" name="creadorTarea" placeholder="Creador" value="' . $creadorTarea . '"><br>';

        $form .= '<label for="observaciones">Ingrese las observaciones de la tarea</label>';
        $form .= '<textarea name="observaciones" placeholder="Observaciones">' . $observaciones . '</textarea><br>';

        $form .= generateSelect('Empleado', $empleados, $idEmpleado);
        $form .= generateSelect('Estado', $estados, $idEstado);
        $form .= generateSelect('Prioridad', $prioridades, $idPrioridad);
    }

    $form .= '<button type="submit">Guardar Tarea</button>';
    $form .= '</form>';

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
    function estado($data)
    {
        $form = '<h2>Modificar Estado</h2>';
        $form .= '<form action="confirmarEstado.php" method="post" id="formEstado">';
        if (!empty($data['cod'])) {
            $form .= '<input type="hidden" name="cod" value="' . $data['cod'] . '">';
        }
        $form .= '<br>';
        $form .= '    <div class="campoFormulario">';
        $form .= '        <label class="textoEjem" for="idEstado">Estado</label>';
        $form .= '        <div id="selectEstado" class="campoFormulario">';
        $form .= (new EstadosViews())->getSelect();
        $form .= '        </div>';
        $form .= '    </div>';
        $form .= '<br>';
        $form .= '  <div class="campoFormulario botonFormulario">';
        $form .= '      <button type="submit" class="btnFormulario">Guardar</button>';
        $form .= '  </div>';
        $form .= '</form>';
        return $form;
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

function empleado($data)
{
    $form = '<h1>Modificar Empleado</h1>';
    $form .= '<form action="confirmarEmpleado.php" method="post" id="formEmpleado">';
    if (!empty($data['cod'])) {
        $form .= '<input type="hidden" name="cod" value="' . $data['cod'] . '">';
    }
    $form .= '<br>';
    $form .= '    <div class="campoFormulario">';
    $form .= '        <label class="textoEjem" for="idEmpleado">Empleado</label>';
    $form .= '        <div id="selectEmpleado" class="campoFormulario">';
    $form .= (new EmpleadosViews())->getSelect();
    $form .= '        </div>';
    $form .= '    </div>';
    $form .= '<br>';
    $form .= '  <div class="campoFormulario botonFormulario">';
    $form .= '      <button type="submit" class="btnFormulario">Guardar</button>';
    $form .= '  </div>';
    $form .= '</form>';
    return $form;
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

        $form = '<form action="confirmarRegistro.php" method="post">';
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