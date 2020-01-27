<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuCambioSalario;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoMotivo;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionAdicional;
use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Entity\RecursoHumano\RhuParametroPrestacion;
use App\Form\Type\RecursoHumano\ContratoActualizarTerminadoType;
use App\Form\Type\RecursoHumano\ContratoParametrosInicialesType;
use App\Form\Type\RecursoHumano\ContratoType;
use App\Formato\RecursoHumano\Contrato;
use App\General\General;
use App\Utilidades\Formato;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContratoMotivoController extends MaestroController
{


}