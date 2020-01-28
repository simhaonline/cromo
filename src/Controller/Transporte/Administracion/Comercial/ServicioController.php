<?php
namespace App\Controller\Transporte\Administracion\Comercial;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Transporte\TteDescuentoZona;
use App\Entity\Transporte\TteServicio;
use App\Form\Type\Transporte\CondicionType;
use App\Form\Type\Transporte\CondicionFleteType;
use App\Utilidades\Estandares;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class ServicioController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "TteServicio";


    protected $class = TteServicio::class;
    protected $claseNombre = "TteServicio";
    protected $modulo = "Transporte";
    protected $funcion = "Administracion";
    protected $grupo = "Comercial";
    protected $nombre = "Servicio";

}

