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

use App\views\TareasView;

$tareasView = new TareasView();
$datosFormulario = $_POST;
$msg = empty($datosFormulario['cod'])
  ? $tareasView->getMsgNewTarea($datosFormulario)
  : $tareasView->getMsgUpdateTarea($datosFormulario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar acción</title>
</head>
<body>
    <header>
        <h1>Estado de acción</h1>
    </header>
    <section>
        <?php echo $msg;?>
        <br>
        <a href="inicio.php">Volver al inicio</a>
    </section>
</body>
</html> 