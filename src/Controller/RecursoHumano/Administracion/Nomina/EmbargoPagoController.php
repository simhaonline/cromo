<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEmbargoTipo;
use App\Form\Type\RecursoHumano\EmbargoTipoType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmbargoPagoController extends MaestroController
{


    public $tipo = "administracion";
    public $modelo = "RhuEmbargoPago";


}