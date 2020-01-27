<?php

namespace App\Controller\Tesoreria\Administracion;

use App\Controller\BaseController;
use App\Controller\MaestroController;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Tesoreria\TerceroType;
use App\General\General;
use App\Utilidades\Estandares;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CuentaPagarTipo extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TesCuentaPagarTipo";

}
