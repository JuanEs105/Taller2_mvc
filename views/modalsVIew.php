<?php

namespace App\views;

class ModalsView
{

    function getConfirmationModal($idModal, $nameForm, $url)
    {
        $modal = '<div id="'.$idModal.'" class="modal ocultarModal">';
        $modal .= '  <form name="'.$nameForm.'" action="'.$url.'" method="post" class="confirmation">';
        $modal .= '     <input type="hidden" name="cod">';
        $modal .= '     <button type="button" class="closeBtn">X</button>';
        $modal .= '     <p class="msg">¿Desea eliminar el registro?</p>';
        $modal .= '     <button type="submit" class="okBtn">Si</button>';
        $modal .= '     <button type="button" class="notBtn">No</button>';
        $modal .= '  </form>';
        $modal .= '</div>';
        return $modal;
    }
}