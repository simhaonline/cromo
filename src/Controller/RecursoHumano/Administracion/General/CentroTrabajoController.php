<?php


namespace App\Controller\RecursoHumano\Administracion\General;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuCentroTrabajo;
use App\Form\Type\RecursoHumano\CargoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\General\General;

class CentroTrabajoController  extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "RhuCentroTrabajo";

    protected $clase = RhuCentroTrabajo::class;
    protected $claseNombre = "RhuCentroTrabajo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "CentroTrabajo";

}