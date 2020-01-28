<?php

namespace App\Controller\Inventario\Movimiento\Comercial;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvConfiguracion;
use App\Entity\Inventario\InvContacto;
use App\Entity\Inventario\InvCotizacionDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\CotizacionType;
use App\Formato\Inventario\Cotizacion;
use App\Formato\Inventario\Cotizacion2;
use App\General\General;
use App\Utilidades\Mensajes;
use App\Utilidades\Estandares;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionTipo;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CotizacionTipoController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "InvCotizacionTipo";

    protected $class = InvCotizacionTipo::class;
    protected $claseNombre = "InvCotizacionTipo";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Comercial";
    protected $nombre = "CotizacionTipo";



}
