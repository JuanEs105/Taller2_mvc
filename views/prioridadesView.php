<?php
namespace App\views;

use App\models\entities\Prioridad;

class PrioridadesViews {
    public function getSelect($includeDefault = false) {
        $prioridades = Prioridad::all();
        $options = $includeDefault ? '<option value="">Seleccione una prioridad</option>' : '';

        foreach ($prioridades as $prioridad) {
            $options .= '<option value="' . $prioridad->get('id') . '">' . $prioridad->get('nombre') . '</option>';
        }

        return '
            <div class="form-group">
                <label for="idPrioridad">Prioridad</label>
                <select id="idPrioridad" name="idPrioridad" required>
                    ' . $options . '
                </select>
            </div>
        ';
    }
}
?>
