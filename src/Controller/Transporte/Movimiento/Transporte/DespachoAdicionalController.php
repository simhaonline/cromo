<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenProceso;
use App\Entity\Seguridad\SegUsuarioProceso;
use App\Entity\Transporte\TteAuxiliar;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteConductor;
use App\Entity\Transporte\TteConfiguracion;
use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteDespachoAdicional;
use App\Entity\Transporte\TteDespachoAuxiliar;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteMonitoreoRegistro;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TtePoseedor;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteUbicacion;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\DespachoLiquidarType;
use App\Form\Type\Transporte\DespachoType;
use App\Form\Type\Transporte\NovedadType;
use App\Formato\Transporte\CobroEntrega;
use App\Formato\Transporte\Despacho;
use App\Formato\Transporte\Liquidacion;
use App\Formato\Transporte\Manifiesto;
use App\Formato\Transporte\RelacionEntrega;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DespachoAdicionalController extends MaestroController
{
    public $tipo = "movimiento";
    public $modelo = "TteDespachoAdicional";

    protected $clase = TteDespachoAdicional::class;
    protected $claseNombre = "TteDespachoAdicional";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "DespachoAdicional";


}