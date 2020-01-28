<?php

namespace App\Controller\Transporte\Administracion\Vehiculo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteTipoCombustible;
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

class TipoCombustibleController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TteTipoCombustible";

    protected $class = TteTipoCombustible::class;
    protected $claseNombre = "TteTipoCombustible";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Vehiculo";
    protected $nombre = "TipoCombustible";

}

