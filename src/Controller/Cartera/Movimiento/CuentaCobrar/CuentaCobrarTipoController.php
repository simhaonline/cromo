<?php

namespace App\Controller\Cartera\Movimiento\CuentaCobrar;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Cartera\CuentaCobrarEditarType;
use App\Form\Type\Cartera\CuentaCobrarType;
use App\General\General;
use App\Utilidades\Estandares;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CuentaCobrarTipoController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "CarCuentaCobrarTipo";

    protected $clase = CarCuentaCobrarTipo::class;
    protected $claseNombre = "CarCuentaCobrarTipo";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "CuentaCobrar";
    protected $nombre = "CuentaCobrarTipo";

}