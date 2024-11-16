<?php
namespace App\views;

use App\models\entities\Empleado;

class EmpleadosViews {
    public function getSelect($includeDefault = false) {
        $empleados = Empleado::all();
        $options = $includeDefault ? '<option value="">Seleccione un empleado</option>' : '';

        foreach ($empleados as $empleado) {
            $options .= '<option value="' . $empleado->get('id') . '">' . $empleado->get('nombre') . '</option>';
        }

        return '
            <div class="form-group">
                <label for="idEmpleado">Empleado Responsable</label>
                <select id="idEmpleado" name="idEmpleado" required>
                    ' . $options . '
                </select>
            </div>
        ';
    }
}
?>
