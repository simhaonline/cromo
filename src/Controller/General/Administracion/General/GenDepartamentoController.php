<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenDepartamento;


class GenDepartamentoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "GenDepartamento";


    protected $clase= GenDepartamento::class;
    protected $claseNombre = "GenDepartamento";
    protected $modulo   = "General";
    protected $funcion  = "Administracion";
    protected $grupo    = "General";
    protected $nombre   = "GenDepartamento";




}