<?php
namespace App\views;

use App\models\entities\Estado;

class EstadosViews {
    public function getSelect($includeDefault = false) {
        $estados = Estado::all();
        $options = $includeDefault ? '<option value="">Seleccione un estado</option>' : '';

        foreach ($estados as $estado) {
            $options .= '<option value="' . $estado->get('id') . '">' . $estado->get('nombre') . '</option>';
        }

        return '
            <div class="form-group">
                <label for="idEstado">Estado</label>
                <select id="idEstado" name="idEstado">
                    ' . $options . '
                </select>
            </div>
        ';
    }
}
?>
