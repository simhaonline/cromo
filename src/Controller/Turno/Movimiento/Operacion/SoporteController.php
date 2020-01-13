<?php

namespace App\Controller\Turno\Movimiento\Operacion;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurFestivo;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurProgramacionRespaldo;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Entity\Turno\TurSoporteHora;
use App\Form\Type\Turno\ContratoDetalleType;
use App\Form\Type\Turno\PedidoType;
use App\Form\Type\Turno\PedidoDetalleType;
use App\Form\Type\Turno\SoporteType;
use App\Formato\Inventario\Pedido;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SoporteController extends ControllerListenerGeneral
{
    protected $clase = TurSoporte::class;
    protected $claseNombre = "TurSoporte";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Operacion";
    protected $nombre = "Soporte";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/operacion/soporte/lista", name="turno_movimiento_operacion_soporte_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroTurSoporteLista')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoSoportePk', TextType::class, array('required' => false, 'data'=>$raw['filtros']['codigoSoportePk']))
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAprobado'] ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAnulado'] ])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurSoporte::class)->lista($raw), "Soporte");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                /*set_time_limit(0);
                ini_set("memory_limit", -1);
                $arSoporte = $em->getRepository(TurSoporte::class)->find(8);
                $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->listaHoras($arSoporte->getCodigoSoportePk(), null);
                foreach ($arSoportesContratos as $arSoporteContrato) {
                    $em->getRepository(TurProgramacionRespaldo::class)->generar($arSoporte, $arSoporteContrato);
                }
                $em->flush();*/
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(TurSoporte::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_lista'));
            }
        }
        $arSoportes = $paginator->paginate($em->getRepository(TurSoporte::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('turno/movimiento/operacion/soporte/lista.html.twig', [
            'arSoportes' => $arSoportes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/movimiento/operacion/soporte/nuevo/{id}", name="turno_movimiento_operacion_soporte_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSoporte = new TurSoporte();
        if ($id != '0') {
            $arSoporte = $em->getRepository(TurSoporte::class)->find($id);
            if (!$arSoporte) {
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_lista'));
            }
        } else {
            $arSoporte->setFechaDesde(new \DateTime('now'));
            $arSoporte->setFechaHasta(new \DateTime('now'));
            $arSoporte->setFechaHastaPeriodo(new \DateTime('now'));
        }
        $form = $this->createForm(SoporteType::class, $arSoporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arSoporte->setUsuario($this->getUser()->getUserName());
                }
                $dias = ($arSoporte->getFechaDesde()->diff($arSoporte->getFechaHasta()))->days + 1;
                if($dias >=31) {
                    $dias = 30;
                }
                $arSoporte->setDias($dias);
                $arFestivos = $em->getRepository(TurFestivo::class)->festivos($arSoporte->getFechaDesde()->format('Y-m-d'), $arSoporte->getFechaHasta()->format('Y-m-d'));
                $arrDias = $this->festivosDomingos($arSoporte->getFechaDesde(), $arSoporte->getFechaHasta(), $arFestivos);
                $arSoporte->setDomingos($arrDias['domingos']);
                $arSoporte->setFestivos($arrDias['festivos']);
                $em->persist($arSoporte);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $arSoporte->getCodigoSoportePk()]));
            }
        }
        return $this->render('turno/movimiento/operacion/soporte/nuevo.html.twig', [
            'arSoporte' => $arSoporte,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/turno/movimiento/operacion/soporte/detalle/{id}", name="turno_movimiento_operacion_soporte_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroTurSoporteDetalle')
        ];
        $em = $this->getDoctrine()->getManager();
        $arSoporte = $em->getRepository(TurSoporte::class)->find($id);
        $form = Estandares::botonera($arSoporte->getEstadoAutorizado(), $arSoporte->getEstadoAprobado(), $arSoporte->getEstadoAnulado());
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']));
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnCargarContratos = ['label' => 'Cargar contratos', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arSoporte->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnAprobado['disabled'] = false;
            $arrBtnCargarContratos['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arSoporte->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
        }
        $form->add('btnCargarContratos', SubmitType::class, $arrBtnCargarContratos);
        $form->add('btnEliminarDetalle', SubmitType::class, $arrBtnEliminar);
        $form->add('identificacion', TextType::class,array('required' => false,  'data'=>$raw['filtros']['identificacion'] ));
        $form->add('btnFiltrar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Filtrar']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TurSoporte::class)->autorizar($arSoporte);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurSoporte::class)->desAutorizar($arSoporte);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
            }
            if($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurSoporte::class)->aprobar($arSoporte);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
            }
            if($form->get('btnCargarContratos')->isClicked()) {
                $em->getRepository(TurSoporte::class)->cargarContratos($arSoporte);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrDetalles = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TurSoporteContrato::class)->retirarDetalle($arrDetalles);
                return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
            }
            if ($form->get('btnExcel')->isClicked()){
                $raw['filtros'] = $this->getFiltrosDetalle($form);
                $arSoportes = $em->getRepository(TurSoporteContrato::class)->lista($raw, $id);
                $this->exportarExcel($arSoportes);
            }
            if ($form->get('btnFiltrar')->isClicked()){
                $raw['filtros'] = $this->getFiltrosDetalle($form);
            }
        }
        $arSoporteContratos = $paginator->paginate($em->getRepository(TurSoporteContrato::class)->lista($raw, $id), $request->query->getInt('page', 1), 500);

        return $this->render('turno/movimiento/operacion/soporte/detalle.html.twig', [
            'form' => $form->createView(),
            'arSoporte' => $arSoporte,
            'arSoporteContratos' => $arSoporteContratos,
            'clase' => array('clase' => 'TurSoporte', 'codigo' => $id),
        ]);
    }

    /**
     * @Route("/turno/movimiento/operacion/soportecontrato/resumen/{id}", name="turno_movimiento_operacion_soportecontrato_resumen")
     */
    public function resumen(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSoporteContrato = $em->getRepository(TurSoporteContrato::class)->find($id);
        $arrBtnActualizar = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Actualizar'];
        $form = $this->createFormBuilder()
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurSoporteHora::class)->retirarSoporteContrato($id);
                $arrFestivos = $em->getRepository(TurFestivo::class)->fecha($arSoporteContrato->getFechaDesde()->format('Y-m-') . '01', $arSoporteContrato->getFechaHasta()->format('Y-m-t'));
                $arSoportesContratos = $em->getRepository(TurSoporteContrato::class)->listaHoras(null, $id);
                foreach ($arSoportesContratos as $arSoportesContratoProcesar) {
                    $em->getRepository(TurSoporteContrato::class)->generarHoras($arSoporteContrato->getSoporteRel(), $arSoportesContratoProcesar, $arrFestivos);
                }
                $em->flush();
                $em->getRepository(TurSoporte::class)->resumen($arSoporteContrato->getSoporteRel());

                if($arSoporteContrato->getCodigoDistribucionFk()) {
                    $em->getRepository(TurSoporteContrato::class)->distribuir($arSoporteContrato->getSoporteRel(), array($arSoporteContrato->getCodigoSoporteContratoPk()));
                }
                $em->flush();

            }
            return $this->redirect($this->generateUrl('turno_movimiento_operacion_soportecontrato_resumen', ['id' => $id]));
        }

        $arSoporteHoras = $em->getRepository(TurSoporteHora::class)->soporteContrato($id);

        $dateFecha = (new \DateTime('now'));
        $arrDiaSemana = FuncionesController::diasMes($dateFecha,  $em->getRepository(TurFestivo::class)->festivos($dateFecha->format('Y-m-') . '01', $dateFecha->format('Y-m-t')));
        $arProgramacionDetalle = $em->getRepository(TurProgramacion::class)->findBy(array('anio' => $arSoporteContrato->getAnio(), 'mes' => $arSoporteContrato->getMes(), 'codigoEmpleadoFk'=>$arSoporteContrato->getEmpleadoRel()->getCodigoEmpleadoPK()));
        return $this->render('turno/movimiento/operacion/soporte/resumen.html.twig', [
            'arSoporteHoras' => $arSoporteHoras,
            'arSoporteContrato' => $arSoporteContrato,
            'arProgramacionDetalles'=>$arProgramacionDetalle,
            'arrDiaSemana'=>$arrDiaSemana,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoSoportePk' => $form->get('codigoSoportePk')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
        ];
        $session->set('filtroTurSoporteLista', $filtro);
        return $filtro;
    }

    public function exportarExcel($arSoportes)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arSoportes) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('soporte');
            $j = 0;
            $arrColumnas = ['COD', 'NIT', 'EMPLEADO', 'CT',
                'D', 'DT', 'NOV','IND', 'ING','RET', 'INC', 'LIC', 'LNR', 'AUS', 'VAC',
                'H', 'DS', 'HD', 'HN','HFD', 'HFN','HED','HEN','HEFD','HEFN','RN','RFD','RFN','R',
                'DSP','SALARIO', 'DEV_PAC', 'VR_SALARIO','TTE', 'A_DP', 'A1',
                'HD_R','D_R', 'N_R','FD_R','FN_R','EFN_R','R_R','RN_R','TOTAL'];

            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arSoportes as $soporte) {
                $total = $soporte['vrHoras'] + $soporte['vrAuxilioTransporte'] + $soporte['vrAdicionalDevengadoPactado'] + $soporte['vrAdicional1'];
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $soporte['codigoEmpleadoFk']);
                $hoja->setCellValue('B' . $j, $soporte['numeroIdentificacion']);
                $hoja->setCellValue('C' . $j, $soporte['empleado']);
                $hoja->setCellValue('D' . $j, $soporte['codigoContratoFk']);
                $hoja->setCellValue('E' . $j, $soporte['dias']);
                $hoja->setCellValue('F' . $j, $soporte['diasTransporte']);
                $hoja->setCellValue('G' . $j, $soporte['novedad']);
                $hoja->setCellValue('H' . $j, $soporte['induccion']);
                $hoja->setCellValue('I' . $j, $soporte['ingreso']);
                $hoja->setCellValue('J' . $j, $soporte['retiro']);
                $hoja->setCellValue('K' . $j, $soporte['incapacidad']);
                $hoja->setCellValue('L' . $j, $soporte['licencia']);
                $hoja->setCellValue('M' . $j, $soporte['licenciaNoRemunerada']);
                $hoja->setCellValue('N' . $j, $soporte['ausentismo']);
                $hoja->setCellValue('O' . $j, $soporte['vacacion']);
                $hoja->setCellValue('P' . $j, $soporte['horas']);
                $hoja->setCellValue('Q' . $j, $soporte['horasDescanso']);
                $hoja->setCellValue('R' . $j, $soporte['horasDiurnas']);
                $hoja->setCellValue('S' . $j, $soporte['horasNocturnas']);
                $hoja->setCellValue('T' . $j, $soporte['horasFestivasDiurnas']);
                $hoja->setCellValue('U' . $j, $soporte['horasFestivasNocturnas']);
                $hoja->setCellValue('V' . $j, $soporte['horasExtrasOrdinariasDiurnas']);
                $hoja->setCellValue('W' . $j, $soporte['horasExtrasOrdinariasNocturnas']);
                $hoja->setCellValue('X' . $j, $soporte['horasExtrasFestivasDiurnas']);
                $hoja->setCellValue('Y' . $j, $soporte['horasExtrasFestivasNocturnas']);
                $hoja->setCellValue('Z' . $j, $soporte['horasRecargoNocturno']);
                $hoja->setCellValue('AA' . $j, $soporte['horasRecargoFestivoDiurno']);
                $hoja->setCellValue('AB' . $j, $soporte['horasRecargoFestivoNocturno']);
                $hoja->setCellValue('AC' . $j, $soporte['horasRecargo']);
                $hoja->setCellValue('AD' . $j, $soporte['codigoDistribucionFk']);
                $hoja->setCellValue('AE' . $j, $soporte['vrSalario']);
                $hoja->setCellValue('AF' . $j, $soporte['vrDevengadoPactado']);
                $hoja->setCellValue('AG' . $j, $soporte['vrHoras']);
                $hoja->setCellValue('AH' . $j, $soporte['vrAuxilioTransporte']);
                $hoja->setCellValue('AI' . $j, $soporte['vrAdicionalDevengadoPactado']);
                $hoja->setCellValue('AJ' . $j, $soporte['vrAdicional1']);
                $hoja->setCellValue('AK' . $j, $soporte['horasDescansoReales']);
                $hoja->setCellValue('AL' . $j, $soporte['horasDiurnasReales']);
                $hoja->setCellValue('AM' . $j, $soporte['horasNocturnasReales']);
                $hoja->setCellValue('AN' . $j, $soporte['horasFestivasDiurnasReales']);
                $hoja->setCellValue('AO' . $j, $soporte['horasFestivasNocturnasReales']);
                $hoja->setCellValue('AP' . $j, $soporte['horasExtrasFestivasNocturnasReales']);
                $hoja->setCellValue('AQ' . $j, $soporte['horasRecargoReales']);
                $hoja->setCellValue('AR' . $j, $soporte['horasRecargoNocturnoReales']);
                $hoja->setCellValue('AS' . $j, $total);

                $j++;
            }

            $libro->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=soportes.xls");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }

    private function festivosDomingos($desde, $hasta, $arFestivos)
    {
        $arrDias = array('festivos' => 0, 'domingos' => 0);
        $fechaDesde = date_create($desde->format('Y-m-d'));
        $domingos = 0;
        $festivos = 0;
        while ($fechaDesde <= $hasta) {
            if ($fechaDesde->format('N') == 7) {
                $domingos++;
            }
            if ($this->festivo($arFestivos, $fechaDesde) == true) {
                $festivos++;
            }
            $fechaDesde->modify('+1 day');
        }
        $arrDias['domingos'] = $domingos;
        $arrDias['festivos'] = $festivos;
        return $arrDias;
    }

    public function festivo($arFestivos, $dateFecha)
    {
        $boolFestivo = 0;
        foreach ($arFestivos as $arFestivo) {
            if ($arFestivo['fecha'] == $dateFecha) {
                $boolFestivo = 1;
            }
        }
        return $boolFestivo;
    }

    public function getFiltrosDetalle($form)
    {
        $session = new Session();
        $filtro = [
            'identificacion' => $form->get('identificacion')->getData(),
        ];
        $session->set('filtroTurSoporteDetalle', $filtro);
        return $filtro;
    }
}
