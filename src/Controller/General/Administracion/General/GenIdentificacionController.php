<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenIdentificacion;
use App\Entity\General\GenResolucion;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use App\Form\Type\General\ResolucionType;
use App\Form\Type\RecursoHumano\VacacionTipoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenIdentificacionController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "GenIdentificacion";


    protected $clase= GenIdentificacion::class;
    protected $claseNombre = "GenIdentificacion";
    protected $modulo   = "General";
    protected $funcion  = "Administracion";
    protected $grupo    = "General";
    protected $nombre   = "GenIdentificacion";



}