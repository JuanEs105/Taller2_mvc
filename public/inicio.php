<?php
require '../controller/empleadosController.php';
require '../controller/tareasController.php';
require '../controller/estadosController.php';
require '../controller/prioridadesController.php';

require '../models/db/tareasdb.php';

require '../models/entities/empleados.php';
require '../models/entities/tareas.php';
require '../models/entities/estados.php';
require '../models/entities/prioridades.php';

require '../models/queries/empleadosQueries.php';
require '../models/queries/tareasQueries.php';
require '../models/queries/estadosQueries.php';
require '../models/queries/prioridadesQueries.php';

require '../views/empleadosView.php';
require '../views/tareasView.php';
require '../views/estadosView.php';
require '../views/prioridadesView.php';
require '../views/modalsView.php';



use App\views\EmpleadosViews;
use App\views\TareasView;
use App\views\PrioridadesViews;
use App\views\EstadosViews;
use App\views\ModalsView;

$empleadosView = new EmpleadosViews();
$tareasViews = new TareasView();
$prioridadesView = new PrioridadesViews();
$estadosView = new EstadosViews();
$modalsView = new ModalsView();


$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';
$idPrioridad = isset($_GET['idPrioridad']) ? $_GET['idPrioridad'] : '';
$idEmpleado = isset($_GET['idEmpleado']) ? $_GET['idEmpleado'] : '';
$descripcion = isset($_GET['descripcion']) ? $_GET['descripcion'] : '';
$idEstado = isset($_GET['idEstado']) ? $_GET['idEstado'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/inicio.css">
</head>

<body class="body-inicio">
    <h1 class="titulo">Lista de tareas</h1>
    <a href="formularioCrearTarea.php" class="btn-crear-tarea">Crear tarea</a>
    <br>

    <button onclick="mostrarVentana()" class="btn-filtrar">Filtrar</button>
    <div class="fondoVentana" id="fondoVentana">
        <div class="ventana">
            <span class="cerrarVentana" onclick="cerrarVentana()">X</span>
            <h2>Filtrar por:</h2>
            <form id="formularioFiltro" action="#" class="formulario-filtro">
                <label for="fechaInicio" class="label-filtro">Fecha de inicio</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="input-filtro">

                <label for="fechaFin" class="label-filtro">Fecha de fin</label>
                <input type="date" id="fechaFin" name="fechaFin" class="input-filtro">

                <label for="idPrioridad" class="label-filtro">Ingrese la prioridad de la tarea</label>
                <?php echo $prioridadesView->getSelect(true); ?>

                <label for="idEmpleado" class="label-filtro">Ingrese la persona responsable de la tarea</label>
                <?php echo $empleadosView->getSelect(true); ?>

                <label for="titulo" class="label-filtro">Titulo:</label>
                <input type="text" id="titulo" name="titulo" class="input-filtro">

                <label for="descripcion" class="label-filtro">Descripcion:</label>
                <input type="text" id="descripcion" name="descripcion" class="input-filtro">

                <button type="submit" class="btn-filtrar-submit">Filtrar</button>
            </form>
        </div>
    </div>

    <button onclick="mostrarVentanaAgrupar()" class="btn-agrupar">Agrupar</button>
    <div class="fondoVentanaAgrupar" id="fondoVentanaAgrupar">
        <div class="ventanaAgrupar">
            <span class="cerrarVentanaAgrupar" onclick="cerrarVentanaAgrupar()">X</span>
            <h2>Agrupar por:</h2>
            <form id="formularioAgrupar" action="#" class="formulario-agrupar">
                <label for="idEstado" class="label-agrupar">Ingrese el estado de la tarea</label>
                <?php echo $estadosView->getSelect(true); ?>

                <button type="submit" class="btn-agrupar-submit">Agrupar</button>
            </form>
        </div>
    </div>

    <div class="tabla-tareas">
        <?php
        echo $tareasViews->getTable($titulo, $fechaInicio, $fechaFin, $idPrioridad, $idEmpleado, $descripcion, $idEstado);
        ?>
    </div>

    <?php
    echo $modalsView->getConfirmationModal(
        'tareaEliminarModal',
        'tareaForm',
        'eliminarTarea.php'
    )
    ?>

    <script src="js/tareas.js"></script>
</body>

</html>
