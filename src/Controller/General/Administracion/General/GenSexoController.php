<?php


namespace App\Controller\General\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\General\GenResolucion;
use App\Entity\General\GenSexo;
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

class GenSexoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "GenSexo";


    protected $clase= GenSexo::class;
    protected $claseNombre = "GenSexo";
    protected $modulo   = "General";
    protected $funcion  = "Administracion";
    protected $grupo    = "General";
    protected $nombre   = "GenSexo";


}