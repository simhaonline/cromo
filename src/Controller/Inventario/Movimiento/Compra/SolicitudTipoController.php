<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvOrdenTipo;
use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvSolicitudTipo;
use App\Formato\Inventario\Solicitud;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use App\General\General;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudTipoController extends MaestroController
{
    public $tipo = "movimiento";
    public $modelo = "InvSolicitudTipo";

    protected $class= InvSolicitudTipo::class;
    protected $claseNombre = "InvSolicitudTipo";
    protected $modulo = "Inventario";
    protected $funcion = "Movimiento";
    protected $grupo = "Compra";
    protected $nombre = "SolicitudTipo";

}
