<?php

namespace App\Controller\Inventario\Administracion\Inventario;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvDocumento;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvSubgrupo;
use App\Form\Type\Inventario\DocumentoType;
use App\Form\Type\Inventario\ItemType;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SubgrupoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "InvSubgrupo";


    protected $class= InvSubgrupo::class;
    protected $claseNombre = "InvSubgrupo";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "Inventario";
    protected $nombre = "Subgrupo";


}
