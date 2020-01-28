<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Recogida;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteRecaudoDevolucion;
use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteRecogidaProgramada;
use App\Form\Type\Transporte\RecogidaType;
use App\Formato\Transporte\Recogida;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecogidaProgramadaController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "TteRecogidaProgramada";

    protected $clase = TteRecogidaProgramada::class;
    protected $claseNombre = "TteRecogidaProgramada";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Recogida";
    protected $nombre = "RecogidaProgramada";



}

