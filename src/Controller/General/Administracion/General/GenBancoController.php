<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenBanco;

class GenBancoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "GenBanco";

    protected $clase = GenBanco::class;
    protected $claseNombre = "GenBanco";
    protected $modulo = "General";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "GenBancoController";




}