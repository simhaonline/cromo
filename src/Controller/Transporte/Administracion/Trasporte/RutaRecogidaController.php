<?php

namespace App\Controller\Transporte\Administracion\Trasporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteRutaRecogida;
use App\Formato\Transporte\Vehiculo;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\VehiculoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RutaRecogidaController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TteRutaRecogida";

    protected $class = TteRutaRecogida::class;
    protected $claseNombre = "TteRutaRecogida";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Transporte";
    protected $nombre = "RutaRecogida";


}

