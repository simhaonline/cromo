<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenEstadoCivil;
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

class GenEstadoCivilController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "GenEstadoCivil";


    protected $clase= GenEstadoCivil::class;
    protected $claseNombre = "GenEstadoCivil";
    protected $modulo   = "General";
    protected $funcion  = "Administracion";
    protected $grupo    = "General";
    protected $nombre   = "GenEstadoCivil";



}