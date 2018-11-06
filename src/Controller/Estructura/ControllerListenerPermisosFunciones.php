<?php

namespace App\Controller\Estructura;

use App\Controller\BaseController;

abstract class ControllerListenerPermisosFunciones extends BaseController {

    protected $claseNombre=null;

    /**
     * @return null
     */
    public function getClaseNombre()
    {
        return $this->claseNombre;
    }







}