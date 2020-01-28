<?php

namespace App\Controller\Financiero\Administracion\Contabilidad;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Form\Type\Financiero\CuentaType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FinComprobanteController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "FinComprobante";


    protected $clase= FinComprobante::class;
    protected $claseNombre = "FinComprobante";
    protected $modulo   = "Financiero";
    protected $funcion  = "Administracion";
    protected $grupo    = "Contabilidad";
    protected $nombre   = "FinComprobante";

}

