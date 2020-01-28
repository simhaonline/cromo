<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvMarca;
use App\Entity\Inventario\InvOrden;
use App\Entity\Inventario\InvOrdenDetalle;
use App\Entity\Inventario\InvOrdenTipo;
use App\Entity\Inventario\InvPrecioDetalle;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\OrdenType;
use App\Formato\Inventario\OrdenCompra;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\General\General;
use App\Formato\Inventario\Orden;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrdenTipoController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "InvOrdenTipo";

    protected $class = InvOrdenTipo::class;
    protected $claseNombre = "InvOrdenTipo";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Compra";
    protected $nombre = "OrdenTipo";



}
