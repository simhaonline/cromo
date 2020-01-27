<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinCuenta;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Form\Type\RecursoHumano\ConceptoCuentaType;
use App\Form\Type\RecursoHumano\ConceptoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubtipoCotizanteController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "RhuSubtipoCotizante";

}