<?php


namespace App\Controller\RecursoHumano\Administracion\Seleccion;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuSeleccionReferenciaTipo;
use App\Entity\RecursoHumano\RhuSeleccionTipo;
use App\Form\Type\RecursoHumano\SeleccionTipoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class SeleccionReferenciaTipoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuSeleccionReferenciaTipo";

    protected $clase = RhuSeleccionReferenciaTipo::class;
    protected $claseNombre = "RhuSeleccionReferenciaTipo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Seleccion";
    protected $nombre = "SeleccionReferenciaTipo";


}