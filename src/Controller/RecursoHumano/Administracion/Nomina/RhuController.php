<?php


namespace App\Controller\RecursoHumano\Administracion\Nomina;

use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuPension;
use App\Form\Type\RecursoHumano\PensionType;
use App\General\General;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RhuController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "Rhu";

}