<?php


namespace App\Controller\RecursoHumano\Movimiento\Nomina;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuAdicionalPeriodo;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\AdicionalPeriodoType;
use App\Form\Type\RecursoHumano\AdicionalType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdicionalPeriodoController extends AbstractController
{
    protected $clase = RhuAdicionalPeriodo::class;
    protected $claseFormulario = AdicionalPeriodoType::class;
    protected $claseNombre = "RhuAdicionalPeriodo";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Nomina";
    protected $nombre = "AdicionalPeriodo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/lista", name="recursohumano_movimiento_nomina_adicionalperiodo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroRhuAdicionalPeriodoLista')
        ];
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('estadoCerrado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoCerrado']])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuAdicionalPeriodo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_lista'));
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuAdicionalPeriodo::class)->lista($raw)->getQuery()->getResult(), "Adicional periodo");
            }

        }
        $arAdicionalPeriodos = $paginator->paginate($em->getRepository(RhuAdicionalPeriodo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/lista.html.twig', [
            'arAdicionalPeriodos' => $arAdicionalPeriodos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/nuevo/{id}", name="recursohumano_movimiento_nomina_adicionalperiodo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAdicionalPeriodo = new RhuAdicionalPeriodo();
        if ($id != 0) {
            $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
        } else {
            $arAdicionalPeriodo->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(AdicionalPeriodoType::class, $arAdicionalPeriodo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAdicionalPeriodo = $form->getData();
                $em->persist($arAdicionalPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', array('id' => $arAdicionalPeriodo->getCodigoAdicionalPeriodoPk())));
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/nuevo.html.twig', [
            'arAdicionalPeriodo' => $arAdicionalPeriodo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/detalle/{id}", name="recursohumano_movimiento_nomina_adicionalperiodo_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroRhuAdicionalPeriodoDetalle')
        ];
        $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);

        $arrBtnEliminar = ['attr' => ['class' => 'btn btn-sm btn-danger'], 'label' => 'Eliminar'];
        $form = $this->createFormBuilder()
            ->add('txtCodigoEmpleado', TextType::class, ['required' => false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltrosDetalle($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuAdicional::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', ['id' => $id]));
            }
        }
        $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($id);
        $arAdicionales = $paginator->paginate($em->getRepository(RhuAdicional::class)->adicionalesPorPeriodo($raw, $id), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/nomina/adicionalPeriodo/detalle.html.twig', [
            'arAdicionalPeriodo' => $arAdicionalPeriodo,
            'arAdicionales' => $arAdicionales,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/detalle/nuevo/{codigoAdicional}/{codigoAdicionalPeriodo}", name="recursohumano_movimiento_nomina_adicionalperiodo_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoAdicional, $codigoAdicionalPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var $arAdicionalPerido RhuAdicionalPeriodo */
        $arAdicionalPerido = $em->getRepository(RhuAdicionalPeriodo::class)->find($codigoAdicionalPeriodo);
        $arAdicional = new RhuAdicional();
        if ($codigoAdicional != 0) {
            $arAdicional = $em->getRepository(RhuAdicional::class)->find($codigoAdicional);
        }
        $arAdicional->setAplicaNomina(true);
        $form = $this->createForm(AdicionalType::class, $arAdicional);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($arAdicional->getCodigoEmpleadoFk());
                if($arEmpleado) {
                    $arContrato = null;
                    if ($arEmpleado->getCodigoContratoFk()) {
                        $arContrato = $arEmpleado->getContratoRel();
                    } elseif ($arEmpleado->getCodigoContratoUltimoFk()) {
                        $arContrato = $em->getReference(RhuContrato::class, $arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if ($arContrato) {
                        $arAdicional->setFecha($arAdicionalPerido->getFecha());
                        $arAdicional->setEmpleadoRel($arEmpleado);
                        $arAdicional->setContratoRel($arContrato);
                        $arAdicional->setAdicionalPeriodoRel($arAdicionalPerido);
                        $em->persist($arAdicional);
                        $em->flush();

                        return $this->redirect($this->generateUrl('recursohumano_movimiento_nomina_adicionalperiodo_detalle', ['id' => $arAdicionalPerido->getCodigoAdicionalPeriodoPk()]));
                    } else {
                        Mensajes::error('El empleado no tiene un contrato activo en el sistema');
                    }
                } else {
                    Mensajes::error('El empleado no existe');
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicional/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/nomina/adicionalperiodo/importar/{codigoPeriodo}", name="recursohumano_movimiento_nomina_adicionalperiodo_importar")
     */
    public function importarMasivos(Request $request, $codigoPeriodo)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class)
            ->add('BtnCargar', SubmitType::class, array('label' => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('BtnCargar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $fechaActual = new \DateTime('now');
                $valoresErroneos = [];
                $arrCarga = [];
                $arConfiguracion = $em->getRepository(GenConfiguracion::class)->find(1);

                if ($codigoPeriodo != 0 && $codigoPeriodo != "") {
                    $arAdicionalPeriodo = $em->getRepository(RhuAdicionalPeriodo::class)->find($codigoPeriodo);
                }
                $form['attachment']->getData()->move($arConfiguracion->getRutaTemporal(), "archivo.xls");
                $ruta = $arConfiguracion->getRutaTemporal() . "archivo.xls";
                $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($ruta);
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
                    $nrColumns = ord($highestColumn) - 64;
                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $concepto = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $identificacion = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $valor = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                        $detalle = $cell->getValue();

                        if (!is_numeric($valor)) {
                            $valoresErroneos[] = "- Fila {$row}: '{$valor}'";
                        }
                        if (!is_numeric($concepto)) {
                            $valoresErroneos[] = "- Fila {$row}: '{$concepto}'";
                        }
                        $arrCarga[] = array(
                            'concepto' => $concepto,
                            'identificacion' => $identificacion,
                            'valor' => $valor,
                            'detalle' => $detalle);
                    }
                }
                # $error = "";
                $cedulasNoEncontradas = [];
                $conceptosNoEncontrados = [];
                $sobrepasanLimiteAdicionalPago = [];
                $empleadosSinContratos = [];
                $error = false;
                $mensaje = "";
                if (empty($valoresErroneos)) {
                    foreach ($arrCarga as $carga) {

                        if ($carga['concepto'] != null && $carga['identificacion'] != null && $carga['valor'] != null) {
                            if ($carga['concepto']) {
                                $arConcepto = $em->getRepository(RhuConcepto::class)->find($carga['concepto']);
                            }
                            if ($carga['identificacion']) {
                                $arEmpleado = $em->getRepository(RhuEmpleado::class)->findOneBy(array('numeroIdentificacion' => $carga['identificacion']));
                            }
                            if ($arEmpleado) {
                                $codigoContrato = $arEmpleado->getCodigoContratoFk();
                                if ($codigoContrato == null || $codigoContrato == "") {
                                    $codigoContrato = $arEmpleado->getCodigoContratoUltimoFk();
                                }
                                $arContrato = $em->getRepository(RhuContrato::class)->findOneBy(array('codigoContratoPk' => $codigoContrato));

                                if ($arContrato == null) {
                                    $empleadosSinContratos[] = $carga['identificacion'];
                                } else {
                                    $arCentroCosto = $arContrato->getCentroCostoRel();
                                }
                                if ($arConcepto) {
                                    $arAdicional = new RhuAdicional;
                                    $arAdicional->setConceptoRel($arConcepto);
                                    $arAdicional->setEmpleadoRel($arEmpleado);
                                    $arAdicional->setContratoRel($arEmpleado->getContratoRel());
                                    $arAdicional->setVrValor($carga['valor']);
                                    $arAdicional->setDetalle($carga['detalle']);
                                    $arAdicional->setAdicionalPeriodoRel($arAdicionalPeriodo);
                                    $arAdicional->setAplicaNomina(1);
                                    $arAdicional->setFecha($arAdicionalPeriodo->getFecha());
                                    $em->persist($arAdicional);
                                } else {
                                    $conceptosNoEncontrados[] = $carga['concepto'];
                                }
                            } else {
                                $cedulasNoEncontradas[] = $carga['identificacion'];
                            }
                        }
                    }
                }
                else {
                    $error = true;
                    $mensaje .= ($mensaje != "" ? "<hr>" : "") . "Lo siguientes valores contienen errores: <br>" . implode('<br>', $valoresErroneos);
                    $mensaje .= "<br>Es probable que haya usado formulas.";
                }
                if (!empty($cedulasNoEncontradas)) {
                    $error = true;
                    $mensaje .= "Los siguientes numeros de identificación no se encuentran registrados: <br>" . implode(', ', $cedulasNoEncontradas);
                }
                if (!empty($empleadosSinContratos)) {
                    $error = true;
                    $mensaje .= "Los siguientes numeros de identificación no tienen contratos registrados: <br>" . implode(', ', $empleadosSinContratos);
                }
                if (!empty($conceptosNoEncontrados)) {
                    $error = true;
                    $mensaje .= ($mensaje != "" ? "<hr>" : "") . "Lo siguientes conceptos no se encuentran registrados: <br>" . implode(', ', $conceptosNoEncontrados);
                }
                if (!empty($sobrepasanLimiteAdicionalPago)) {
                    $error = true;
                    $mensaje .= "Los siguientes numeros de identificación sobrepasan el limite de adicional al pago: <br>" . implode(', ', $sobrepasanLimiteAdicionalPago);
                }
                if ($error) {
                    Mensajes::error($mensaje);
                }
                else {
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('recursohumano/movimiento/nomina/adicional/importar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'estadoCerrado' => $form->get('estadoCerrado')->getData(),
        ];
        $session->set('filtroRhuAdicionalPeriodoLista', $filtro);

        return $filtro;
    }

    public function getFiltrosDetalle($form)
    {
        $session = new Session();
        $filtro = [
            'codigoEmpleado' => $form->get('txtCodigoEmpleado')->getData(),
        ];
        $session->set('filtroRhuAdicionalPeriodoDetalle', $filtro);

        return $filtro;
    }
}