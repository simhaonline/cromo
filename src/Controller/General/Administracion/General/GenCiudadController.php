<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenCiudad;


class GenCiudadController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "GenCiudad";


    protected $clase= GenCiudad::class;
    protected $claseNombre = "GenCiudad";
    protected $modulo   = "General";
    protected $funcion  = "Administracion";
    protected $grupo    = "General";
    protected $nombre   = "GenCiudad";




}
