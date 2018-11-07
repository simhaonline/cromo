<?php

namespace App\Controller\Estructura;

use App\Controller\BaseController;

abstract class ControllerListenerGeneral extends BaseController {

    protected $claseNombre=null;

    /**
     * @return null
     */
    public function getClaseNombre()
    {
        return $this->claseNombre;
    }



}