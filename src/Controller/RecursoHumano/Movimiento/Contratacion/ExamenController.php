<?php

namespace App\Controller\RecursoHumano\Movimiento\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenItem;
use App\Entity\RecursoHumano\RhuExamenRestriccionMedica;
use App\Entity\RecursoHumano\RhuExamenRestriccionMedicaDetalle;
use App\Entity\RecursoHumano\RhuExamenRestriccionMedicaTipo;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Form\Type\RecursoHumano\ExamenType;
use App\Form\Type\RecursoHumano\RhuExamenRestriccionMedicaEditarType;
use App\Form\Type\RecursoHumano\RhuExamenRestriccionMedicaType;
use App\Formato\RecursoHumano\Examen;
use App\Formato\RecursoHumano\ExamenRestriccionMedica;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamenController extends MaestroController
{


    public $tipo = "Movimiento";
    public $modelo = "RhuExamen";


    protected $clase = RhuExamen::class;
    protected $claseNombre = "RhuExamen";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Contratacion";
    protected $nombre = "Examen";

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/contratacion/examen/lista", name="recursohumano_movimiento_contratacion_examen_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros' => $session->get('filtroRhuExamen')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoExamenTipoFk', EntityType::class, [
                'class' => RhuExamenTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.codigoExamenTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2'],
                'data' => $raw['filtros']['examenTipo'] ? $em->getReference(RhuExamenTipo::class, $raw['filtros']['examenTipo']) : null
            ])
            ->add('codigoExamenPk', TextType::class, array('required' => false, 'data' => $raw['filtros']['codigoExamen']))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false, 'data' => $raw['filtros']['codigoEmpleado']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $raw['filtros']['fechaDesde'] ? date_create($raw['filtros']['fechaDesde']) : null])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => $raw['filtros']['fechaHasta'] ? date_create($raw['filtros']['fechaHasta']) : null])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data' => $raw['filtros']['estadoAutorizado']])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data' => $raw['filtros']['estadoAprobado']])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data' => $raw['filtros']['estadoAnulado']])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuExamen::class)->lista($raw), "Examenes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuExamen::class)->eliminar($arrSeleccionados);
            }
        }
        $arExamenes = $paginator->paginate($em->getRepository(RhuExamen::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/contratacion/examen/lista.html.twig', [
            'arExamenes' => $arExamenes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/contratacion/examen/nuevo{id}", name="recursohumano_movimiento_contratacion_examen_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamen = new RhuExamen();
        if ($id != '0') {
            $arExamen = $em->getRepository(RhuExamen::class)->find($id);
            if (!$arExamen) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_lista'));
            }
        } else {
            $arExamen->setUsuario($this->getUser()->getUserName());
            $arExamen->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(ExamenType::class, $arExamen);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoEmpleado = $request->request->get('txtCodigoEmpleado');
                if ($txtCodigoEmpleado != '') {
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($txtCodigoEmpleado);
                    if ($arEmpleado) {
                        $arExamen->setEmpleadoRel($arEmpleado);
                        $arExamen->setFecha(new \DateTime('now'));
                        $arExamen->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arExamen->setNombreCorto($arEmpleado->getNombreCorto());
                        $arExamen->setCiudadRel($arEmpleado->getCiudadRel());
                        $arExamen->setEmpleadoRel($arEmpleado);
                        $arExamen->setCodigoSexoFk($arEmpleado->getCodigoSexoFk());
                        if ($id == 0) {
                            $arExamen->setFecha(new \DateTime('now'));
                            $arExamen->setUsuario($this->getUser()->getUserName());
                        }
                        if ($arEmpleado->getCargoRel()) {
                            $arExamen->setCargoRel($arEmpleado->getCargoRel());
                        }
                        $em->persist($arExamen);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_detalle', ['id' => $arExamen->getCodigoExamenPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un Empleado');
                }
            }
        }
        return $this->render('recursohumano/movimiento/contratacion/examen/nuevo.html.twig', [
            'arExamen' => $arExamen,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/recursohumano/movimiento/contratacion/examen/detalle/{id}", name="recursohumano_movimiento_contratacion_examen_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamen = $em->getRepository(RhuExamen::class)->find($id);
        $form = Estandares::botonera($arExamen->getEstadoAutorizado(), $arExamen->getEstadoAprobado(), $arExamen->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnEliminarRestriccion = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnApto = ['label' => 'Apto', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBotonAprobarDetalle = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];

        if ($arExamen->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBotonAprobarDetalle['disabled'] = false;
            $arrBtnApto['disabled'] = false;
        }

        if ($arExamen->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
            $arrBotonAprobarDetalle['disabled'] = true;
            $arrBtnApto['disabled'] = true;
        }
        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->add('btnApto', SubmitType::class, $arrBtnApto);
        $form->add('btnAprobarDetalle', SubmitType::class, $arrBotonAprobarDetalle);
        $form->add('btnEliminarRestriccion', SubmitType::class, $arrBtnEliminarRestriccion);
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuExamenDetalle::class)->eliminar($arrDetallesSeleccionados, $id);
                $em->getRepository(RhuExamen::class)->liquidar($id);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuExamen::class)->autorizar($arExamen);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuExamen::class)->desAutorizar($arExamen);
            }
            if ($form->get('btnApto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuExamen::class)->apto($arExamen);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(RhuExamen::class)->aprobar($arExamen);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(RhuExamenDetalle::class)->actualizarDetalles($arrControles, $form, $arExamen);
                $em->getRepository(RhuExamen::class)->liquidar($id);
            }
            if ($form->get('btnAprobarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuExamenDetalle::class)->aprobar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $id));
                $ExamenRestriccionesMedicas = $em->getRepository(RhuExamenRestriccionMedica::class)->findBy(array('codigoExamenFk' => $id));
                $this->exportarExcelPersonalizado($arExamenDetalles, $ExamenRestriccionesMedicas);
            }
            if ($form->get('btnEliminarRestriccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuExamenRestriccionMedica::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $FormatoExamen = new Examen();
                $FormatoExamen->Generar($em, $id);
            }
            return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_detalle', ['id' => $id]));
        }
        $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $id));

        $arExamenRestriccionesMedicas = $em->getRepository(RhuExamenRestriccionMedica::class)->findBy(array('codigoExamenFk' => $id));
        return $this->render('recursohumano/movimiento/contratacion/examen/detalle.html.twig', array(
            'form' => $form->createView(),
            'arExamen' => $arExamen,
            'arExamenDetalles' => $arExamenDetalles,
            'arExamenRestriccionesMedicas' => $arExamenRestriccionesMedicas,
            'clase' => array('clase' => 'RhuExamen', 'codigo' => $id),

        ));
    }

    /**
     * @param Request $request
     * @param $codigoExamen
     * @param int $codigoExamenDetalle
     * @return Response
     * @Route("/recursohumano/movimiento/contratacion/examen/detalle/nuevo/{codigoExamen}/{id}", name="recursohumano_movimiento_contratacion_examen_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoExamen, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamen = $em->getRepository(RhuExamen::class)->find($codigoExamen);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                if ($arExamen->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados AS $codigo) {
                            $arExamenListaPrecio = $em->getRepository(RhuExamenListaPrecio::class)->find($codigo);
                            $arExamenDetalleValidar = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $codigoExamen, 'codigoExamenItemFk' => $arExamenListaPrecio->getCodigoExamenItemFk()));

                            if (!$arExamenDetalleValidar) {
                                $arExamenItem = $em->getRepository(RhuExamenItem::class)->find($arExamenListaPrecio->getCodigoExamenItemFk());
                                $arExamenDetalle = new RhuExamenDetalle();
                                $arExamenDetalle->setExamenTipoRel($arExamen->getExamenTipoRel());
                                $arExamenDetalle->setExamenItemRel($arExamenItem);
                                $arExamenDetalle->setExamenRel($arExamen);
                                $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getVrPrecio());
                                $em->persist($arExamenDetalle);
                            }
                        }
                        $em->flush();
                        $em->getRepository(RhuExamen::class)->liquidar($codigoExamen);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        Mensajes::error("No selecciono ningun dato para guardar");
                    }
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arrExamenListaPrecios = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoExamenEntidadFk' => $arExamen->getCodigoExamenEntidadFk()));
        $arExamenListaPrecios = $paginator->paginate($arrExamenListaPrecios, $request->query->get('page', 1), 50);
        return $this->render('recursohumano/movimiento/contratacion/examen/detalleNuevo.html.twig', array(
            'arExamenListaPrecios' => $arExamenListaPrecios,
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoExamen' => $form->get('codigoExamenPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arExamenTipo = $form->get('codigoExamenTipoFk')->getData();

        if (is_object($arExamenTipo)) {
            $filtro['examenTipo'] = $arExamenTipo->getCodigoExamenTipoPk();
        } else {
            $filtro['examenTipo'] = $arExamenTipo;
        }
        $session->set('filtroRhuExamen', $filtro);

        return $filtro;

    }

    public function exportarExcelPersonalizado($arExamenDetalles, $ExamenRestriccionesMedicas)
    {
        $em = $this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arExamenDetalles) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja = $libro->createSheet(0);
            //Examen Detalle
            $hoja->setTitle('ExamenDetalle');
            $hoja->setCellValue('A1', 'CODIGO');
            $hoja->setCellValue('B1', 'EXAMEN');
            $hoja->setCellValue('C1', 'ITEM');
            $hoja->setCellValue('D1', 'PRECIO');
            $hoja->setCellValue('E1', 'FECHA');
            $hoja->setCellValue('F1', 'APROBADO');
            $hoja->setCellValue('G1', 'COMENTARIOS');

            $i = 2;
            foreach ($arExamenDetalles as $arExamenDetalle) {
                $hoja->getStyle($i)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $i, $arExamenDetalle->getCodigoExamenDetallePk())
                    ->setCellValue('B' . $i, $arExamenDetalle->getExamenTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arExamenDetalle->getExamenItemRel()->getNombre())
                    ->setCellValue('D' . $i, $arExamenDetalle->getVrPrecio())
                    ->setCellValue('E' . $i, $arExamenDetalle->getExamenRel()->getFecha())
                    ->setCellValue('F' . $i, $arExamenDetalle->getEstadoAprobado() ? "SI" : "NO")
                    ->setCellValue('G' . $i, $arExamenDetalle->getComentario());
                $i++;
            }
            $hoja = $libro->getActiveSheet();
            $hoja = $libro->createSheet(1);
            $hoja->setTitle('RestriccionesMedicas');
            $hoja->setCellValue('A1', 'CODIGO')
                ->setCellValue('B1', 'TIPO REVISION')
                ->setCellValue('C1', 'DIAS')
                ->setCellValue('D1', 'FECHA VENCIMIENTO');

            $i = 2;
            foreach ($ExamenRestriccionesMedicas as $ExamenRestriccionesMedica) {
                $hoja->getStyle($i)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $i, $ExamenRestriccionesMedica->getCodigoExamenRestriccionMedicaPk())
                    ->setCellValue('B' . $i, $ExamenRestriccionesMedica->getExamenRevisionMedicaTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $ExamenRestriccionesMedica->getDias())
                    ->setCellValue('D' . $i, $ExamenRestriccionesMedica->getFechaVence()->format('Y/m/d'));
                $i++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=examen.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }

    /**
     * @Route("/recursohumano/movimiento/contratacion/examen/restriccion/medica/nuevo/{codigoExamen}/{codigoRestriccionMedica}", name="recursohumano_movimiento_contratacion_examen_restriccion_medica_nuevo")
     */
    public function agregarRestriccionMedicaNuevo(Request $request, $codigoExamen, $codigoRestriccionMedica)
    {
        $em = $this->getDoctrine()->getManager();
        $arExamen = $em->getRepository(RhuExamen::class)->find($codigoExamen);
        $arExamenRestriccionMedica = new RhuExamenRestriccionMedica();
        $form = $this->createForm(RhuExamenRestriccionMedicaType::class, $arExamenRestriccionMedica);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($arExamen->getEstadoAutorizado() == 1) {
                    $arUsuario = $this->getUser();
                    $arExamenRestriccionMedica->setExamenRel($arExamen);
                    $arExamenRestriccionMedica->setFecha(new \DateTime('now'));
                    $arExamenRestriccionMedica->setCodigoUsuario($arUsuario->getUserName());
                    //Fecha Vencimiento
                    $fechaExamen = $arExamen->getFecha()->format('Y-m-d');
                    $dias = $arExamenRestriccionMedica->getDias();
                    $dateFechaVence = date('Y-m-d', strtotime("$fechaExamen + $dias day"));
                    $arExamenRestriccionMedica->setFechaVence(new \DateTime($dateFechaVence));
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados AS $codigoExamenRestriccionMedicaDetalle) {
                            $arExamenRestriccionMedicaDetalle = new RhuExamenRestriccionMedicaDetalle();
                            $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaDetalleRel($arExamenRestriccionMedica);
                            $arExamenRestriccionMedicaTipo = $em->getRepository(RhuExamenRestriccionMedicaTipo::class)->find($codigoExamenRestriccionMedicaDetalle);
                            $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaTipoRel($arExamenRestriccionMedicaTipo);
                            $em->persist($arExamenRestriccionMedicaDetalle);
                        }
                        $em->persist($arExamenRestriccionMedica);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }else{
                        Mensajes::error("Debe selecionar al menos un tipo de restricción medica");
                    }
                }else{
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

                }
            }
        }
        $arExamenRestriccionMedicaTipos = $em->getRepository(RhuExamenRestriccionMedicaTipo::class)->findAll();
        return $this->render('recursohumano/movimiento/contratacion/examen/agregarRestriccion.html.twig', array(
            'form' => $form->createView(),
            'arExamenRestriccionMedicaTipos' => $arExamenRestriccionMedicaTipos
        ));
    }

    /**
     * @Route("/recursohumano/movimiento/contratacion/examen/restriccion/medica/editar/{codigoExamen}/{codigoRestriccionMedica}", name="recursohumano_movimiento_contratacion_examen_restriccion_medica_editar")
     */
    public function editarRestriccionMedicaEditar(Request $request, $codigoExamen, $codigoRestriccionMedica){
        $em = $this->getDoctrine()->getManager();
        $arExamen = $em->getRepository(RhuExamen::class)->find($codigoExamen);
        $arExamenRestriccionMedica = new RhuExamenRestriccionMedica();
        $arExamenRestriccionMedicaDetalles = new RhuExamenRestriccionMedicaDetalle();
        if ($codigoRestriccionMedica != 0) {
            $arExamenRestriccionMedica = $em->getRepository(RhuExamenRestriccionMedica::class)->find($codigoRestriccionMedica);
            $arExamenRestriccionMedicaDetalles = $em->getRepository(RhuExamenRestriccionMedicaDetalle::class)->findBy(array('codigoExamenRestriccionMedicaFk' => $arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk()));
            $arExamenRestriccionMedicaTipo = $em->getRepository(RhuExamenRestriccionMedicaDetalle::class)->tiposRestriccionesMedicas($arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk());
        }
        $form = $this->createForm(RhuExamenRestriccionMedicaEditarType::class, $arExamenRestriccionMedica);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arExamen->getEstadoAutorizado() == 1) {
                if ($form->get('guardar')->isClicked()) {
                    $arUsuario = $this->getUser();
                    $arExamenRestriccionMedica = $form->getData();
                    $arExamenRestriccionMedica->setExamenRel($arExamen);
                    $em->persist($arExamenRestriccionMedica);
                    $arExamenRestriccionMedica = $form->getData();
                    $arExamenRestriccionMedica->setExamenRel($arExamen);
                    $arExamenRestriccionMedica->setFecha(new \DateTime('now'));
                    $arExamenRestriccionMedica->setCodigoUsuario($arUsuario->getUserName());
                    $fechaExamen = $arExamen->getFecha()->format('Y-m-d');
                    $dias = $arExamenRestriccionMedica->getDias();
                    $dateFechaVence = date('Y-m-d', strtotime("$fechaExamen + $dias day"));
                    $arExamenRestriccionMedica->setFechaVence(new \DateTime($dateFechaVence));
                    $arrSeleccionados = $request->request->get('ChkSeleccionarNuevo');
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados AS $codigoExamenRestriccionMedicaDetalle) {
                            $arExamenRestriccionMedicaDetalle = new RhuExamenRestriccionMedicaDetalle();
                            $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaDetalleRel($arExamenRestriccionMedica);
                            $arExamenRestriccionMedicaTipo = $em->getRepository(RhuExamenRestriccionMedicaTipo::class)->find($codigoExamenRestriccionMedicaDetalle);
                            $arExamenRestriccionMedicaDetalle->setExamenRestriccionMedicaTipoRel($arExamenRestriccionMedicaTipo);
                            $em->persist($arExamenRestriccionMedicaDetalle);
                        }
                        $em->persist($arExamenRestriccionMedica);
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                if ($form->get('eliminar')->isClicked()) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionarDetalle');
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados AS $codigoExamenRestriccionMedicaDetalle) {
                            $arExamenRestriccionMedicaDetalle = $em->getRepository(RhuExamenRestriccionMedicaDetalle::class)->find($codigoExamenRestriccionMedicaDetalle);
                            $em->remove($arExamenRestriccionMedicaDetalle);
                            $em->flush();
                        }
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_restriccion_medica_editar', array('codigoExamen' => $codigoExamen, 'codigoRestriccionMedica' => $codigoRestriccionMedica)));
                    }
                }
            }else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/contratacion/examen/editarRestriccion.html.twig', array(
            'form' => $form->createView(),
            'arExamenRestriccionMedicaDetalles' => $arExamenRestriccionMedicaDetalles,
            'arExamenRestriccionMedicaTipos' => $arExamenRestriccionMedicaTipo,
        ));
    }

    /**
     * @Route("/recursohumano/movimiento/contratacion/examen/restriccion/medica/detalle/{codigoRestriccionMedica}", name="recursohumano_movimiento_contratacion_examen_restriccion_medica_detalle")
     */
    public function detalleRestriccionMedicaAction(Request $request, $codigoRestriccionMedica){
        $em = $this->getDoctrine()->getManager();

        $arExamenRestriccionMedica = $em->getRepository(RhuExamenRestriccionMedica::class)->find($codigoRestriccionMedica);
        if (!$arExamenRestriccionMedica) {
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arExamenRestriccionMedicaDetalle = $em->getRepository(RhuExamenRestriccionMedicaDetalle::class)->findBy(array('codigoExamenRestriccionMedicaFk' => $arExamenRestriccionMedica->getCodigoExamenRestriccionMedicaPk()));
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('btnImprimir')->isClicked()) {
                $formatoExamenRestriccionMedica = new ExamenRestriccionMedica();
                $formatoExamenRestriccionMedica->Generar($em, $codigoRestriccionMedica, $arExamenRestriccionMedica, $arExamenRestriccionMedicaDetalle);
            }
        }

        return $this->render('recursohumano/movimiento/contratacion/examen/detalleRestriccion.html.twig', array(
            'arExamenRestriccionMedica' => $arExamenRestriccionMedica,
            'arExamenRestriccionMedicaDetalle' => $arExamenRestriccionMedicaDetalle,
            'form' => $form->createView()
        ));
    }

}


