<?php


namespace App\Controller\Turno\Utilidad\Operacion\Importar;


use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\Turno\TurNovedadInconsistencia;
use App\Entity\Turno\TurProgramacion;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Tests\Extension\Core\Type\SubmitTypeTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImportarNovedad extends  AbstractController
{
    /**
     * @Route("turno/utilidad/importar/licencias", name="turno_utilidad_importar_licencias")
     */
    public function listaLicencias(Request $request, PaginatorInterface $paginator )
    {
       return $this->validarNovedades($request, $paginator,$ruta ='licencia',false, true, false, false);
    }

    /**
     * @Route("turno/utilidad/importar/incapacidades", name="turno_utilidad_importar_incapacidades")
     */
    public function listaIncapacidadas(Request $request, PaginatorInterface $paginator )
    {
        return $this->validarNovedades($request, $paginator,$ruta ='incapacidad',false, false, true, false);
    }

    /**
     * @Route("turno/utilidad/importar/vacaciones", name="turno_utilidad_importar_vacaciones")
     */
    public function listaVacaciones(Request $request, PaginatorInterface $paginator )
    {
        return $this->validarNovedades($request, $paginator,$ruta ='vacacion',true, false, false, false);
    }

    /**
     * @Route("turno/utilidad/programacion/importar/novadades/contrato/{codigoEmpleado}/{desde}/{hasta}/{contrato}", name="turno_utilidad_importar_licencias_novedades_contrato")
     */
    public function novedades_consultar_contrato($codigoEmpleado,$desde,$hasta,$contrato){
        $em = $this->getDoctrine()->getManager();
        $arLicencias = $em->getRepository(TurNovedadInconsistencia::class)->listarLicencia($codigoEmpleado, $contrato, $desde, $hasta);

        return $this->render('turno/utilidad/operacion/importar/licenciasNovedades.html.twig', [
            'arLicencias'=>$arLicencias
        ]);
    }


    /**
     * @Route("turno/utilidad/programacion/importar/vacaciones/novadades/contrato/{codigoEmpleado}/{desde}/{hasta}/{contrato}", name="turno_utilidad_importar_vacaciones_novedades_contrato")
     */
    public function novedades_consultar_contrato_vacaciones($codigoEmpleado,$desde,$hasta,$contrato){
        $em = $this->getDoctrine()->getManager();
        $arLicencias = $em->getRepository(TurNovedadInconsistencia::class)->listarLicencia($codigoEmpleado, $contrato, $desde, $hasta);

        return $this->render('turno/utilidad/operacion/importar/licenciasNovedades.html.twig', [
            'arLicencias'=>$arLicencias
        ]);
    }    /**

    /**
    * @Route("turno/utilidad/programacion/importar/incapacidades/novadades/contrato/{codigoEmpleado}/{desde}/{hasta}/{contrato}", name="turno_utilidad_importar_incapacidades_novedades_contrato")
     */
    public function novedades_consultar_contrato_incapacidades($codigoEmpleado,$desde,$hasta,$contrato){
        $em = $this->getDoctrine()->getManager();
        $arLicencias = $em->getRepository(TurNovedadInconsistencia::class)->listarLicencia($codigoEmpleado, $contrato, $desde, $hasta);

        return $this->render('turno/utilidad/operacion/importar/licenciasNovedades.html.twig', [
            'arLicencias'=>$arLicencias
        ]);
    }

    /**
     * @Route("turno/utilidad/programacion/importar/novedades/empleado/contrato/{codigoEmpleado}", name="turno_utilidad_programacion_importar_novadades_empleado_contrato")
     */
    public function mostrarUltimoContratoRecurso($codigoEmpleado)
    {
        $em = $this->getDoctrine()->getManager();

        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigoEmpleado);
        if ($arEmpleado->getCodigoContratoFk()) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($arEmpleado->getCodigoContratoFk());
        } else {
            $arContrato = null;
        }

        return $this->render('turno/utilidad/operacion/importar/consultarContrato.html.twig', [
            'arContrato' => $arContrato,
        ]);
    }

    /**
     * @Route("turno/utilidad/programacion/importar/novedades/empleado/contrato/{codigoEmpleado}/{fechaDesde}", name="turno_utilidad_importar_programacion_empleado")
     */
    public function importarProgramacionEmpleado($codigoEmpleado, $fechaDesde)
    {

        $em = $this->getDoctrine()->getManager();
        $fecha = date_create($fechaDesde);
        $anio = $fecha->format("Y");
        $mes = $fecha->format("m");

        $strAnioMes = $anio . "/" . $mes;
        $arrDiaSemana = array();
        for ($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }

        $arProgramaciones = $em->getRepository(TurProgramacion::class)->findBy(array('anio' => $anio, 'mes' => $mes, 'codigoEmpleadoFk' => $codigoEmpleado));

        return $this->render('turno/utilidad/operacion/importar/consultarProgramacionEmpleado.html.twig', [
            'arProgramaciones' => $arProgramaciones,
            'arrDiaSemana' => $arrDiaSemana,

        ]);
    }

    private function validarNovedades($request,$paginator, $ruta, $vacaciones = false, $licencias = false, $incapacidades = false, $ingresosRetiros = false){
        $em = $this->getDoctrine()->getManager();
        $fechaActual = new \DateTime('now');

        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('txtNombreCorto', TextType::class, array('required' => false, 'attr'=>['readonly'=>true]))
            ->add('fecha', DateType::class, ['label' => 'Fecha desde: ', 'data'=>$fechaActual,'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('btnValidar', SubmitType::class, array('label' => 'Validar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        switch ($ruta) {
            case 'vacacion':
                $importeRuta = '_vacaciones';
                break;
            case 'licencia':
                $importeRuta = '_licencias';
                break;
            case 'incapacidad':
                $importeRuta = '_incapacidades';
                break;
            default:
                $importeRuta = '';
                break;
        }
        $tipoFiltro = [
            "vacacion" => $vacaciones,
            "licencia" => $licencias,
            "incapacidad" => $incapacidades,
            "ingresosRetiros" => $ingresosRetiros,
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnValidar')->isClicked()) {
                $empleado = $form->get("codigoEmpleadoFk")->getData();
                $em->getRepository(TurProgramacion::class)->validarNovedadesRecursoHumano($form->get("fecha")->getData(), $empleado, $tipoFiltro, $form, $this->getUser()->getUsername());
                return $this->redirect($this->generateUrl('turno_utilidad_importar' . $importeRuta));
            }
            if ($request->get("btnImportar") != "") {
                $respuesta = $em->getRepository(TurProgramacion::class)->importarNovedad($request->get("btnImportar"));
                if ($respuesta != '') {
                    $objMensaje->Mensaje("error", $respuesta);
                }
                return $this->redirect($this->generateUrl('brs_tur_utilidad_programacion_importar_novedades' . $importeRuta));
            }
        }
        $arInconsistencias = $paginator->paginate($em->getRepository( TurNovedadInconsistencia::class)->lista($this->getUser()->getUsername(), $tipoFiltro), $request->query->getInt('page', 1), 30);
        return $this->render('turno/utilidad/operacion/importar/licencias.html.twig', [
            'arInconsistencias' => $arInconsistencias,
            'tipoIncapacidad' => TurNovedadInconsistencia::TP_INCAPACIDAD,
            'tipoIngreso' => TurNovedadInconsistencia::TP_INGRESO,
            'tipoRetiro' => TurNovedadInconsistencia::TP_RETIRO,
            'tipoLicencia' => TurNovedadInconsistencia::TP_LICENCIA,
            'tipoVacacion' => TurNovedadInconsistencia::TP_VACACION,
            'form' => $form->createView(),
        ]);
    }

    public function devuelveDiaSemanaEspaniol($dateFecha)
    {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "L";
                break;
            case 2:
                $strDia = "M";
                break;
            case 3:
                $strDia = "I";
                break;
            case 4:
                $strDia = "J";
                break;
            case 5:
                $strDia = "V";
                break;
            case 6:
                $strDia = "S";
                break;
            case 7:
                $strDia = "D";
                break;
        }

        return $strDia;
    }

}