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
require '../views/modalsView.php';


use App\views\TareasView;

$tareasViews = new TareasView();
$title = empty($_GET['cod'])?'Registrar tarea':'Modificar tarea';
$form = $tareasViews->getFormTarea($_GET);
?>
<!DOCTYPE html> 
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario tareas</title>
    <link rel="stylesheet" href="css/formularioCrearTarea.css">

</head>

<body>
    <header>
        <h1><?php echo $title;?></h1>
    </header>
    <section>
        <?php echo $form;?>
    </section>
    <a href="inicio.php">Volver</a>
</body>

</html>