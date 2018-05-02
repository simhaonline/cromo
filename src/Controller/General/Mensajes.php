<?php

namespace App\Controller\General;
use Symfony\Component\HttpFoundation\Session\Session;

class Mensajes {
    const TP_ERROR = "error";
    const TP_OK = "ok";
    const TP_INFO = "info";

    /**
     * Construye los parametros requeridos para generar un mensaje
     * @param string $strTipo El tipo de mensaje a generar  se debe enviar en minuscula <br> error, informacion
     * @param string $strMensaje El mensaje que se mostrara
     */
    public function Mensaje($strTipo, $strMensaje) {
        $session = new Session();
        $session->getFlashBag()->add($strTipo, $strMensaje);
    }

    /**
     * @param $respuesta string
     * @param $objMensaje Mensajes
     */
    public function validarRespuesta($respuesta)
    {
        if ($respuesta != '') {
            $this->Mensaje('error', $respuesta);
        } else {
            $objMensaje = '';
            $this->getDoctrine()->getManager()->flush();
        }
        return $this;
    }
}
