<?php


namespace App\Controller\RecursoHumano\Administracion\General;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Form\Type\RecursoHumano\GrupoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class EntidadController extends  MaestroController
{


    public $tipo = "administracion";
    public $modelo = "RhuEntidad";

    protected $clase = RhuEntidad::class;
    protected $claseNombre = "RhuEntidad";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Entidad";

}