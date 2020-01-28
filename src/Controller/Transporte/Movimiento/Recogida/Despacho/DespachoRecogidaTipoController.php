<?php

namespace App\Controller\Transporte\Movimiento\Recogida\Despacho;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespachoRecogida;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TtePoseedor;
use App\Entity\Transporte\TteRutaRecogida;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoRecogidaType;
use App\Entity\Transporte\TteDespachoRecogidaAuxiliar;
use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteAuxiliar;
use App\Entity\Transporte\TteMonitoreo;
use App\Formato\Transporte\DespachoRecogida;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Count;

class DespachoRecogidaTipoController extends MaestroController
{
    public $tipo = "movimiento";
    public $modelo = "TteDespachoRecogidaTipo";

    protected $clase = TteDespachoRecogidaTipo::class;
    protected $claseNombre = "TteDespachoRecogidaTipo";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Movimiento";
    protected $nombre = "DespachoRecogidaTipo";


}

