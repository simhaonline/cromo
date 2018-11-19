<?php

namespace App\Controller\Estructura;

use App\Controller\BaseController;

abstract class ControllerListenerGeneral extends BaseController {

    protected $claseNombre=null;

    protected $proceso=null;

    protected $nombreProceso=null;

    /**
     * @return null
     */
    public function getClaseNombre()
    {
        return $this->claseNombre;
    }

    /**
     * @return null
     */
    public function getProceso()
    {
        return $this->proceso;
    }

    /**
     * @return null
     */
    public function getNombreProceso()
    {
        return $this->nombreProceso;
    }







}