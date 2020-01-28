<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenCuenta;


class GenCuentaController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "GenCuenta";

    protected $clase = GenCuenta::class;
    protected $claseNombre = "GenCuenta";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "GenCuenta";


}