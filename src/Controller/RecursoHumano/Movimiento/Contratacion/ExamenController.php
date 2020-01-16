<?php

namespace App\Controller\RecursoHumano\Movimiento\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenRestriccionMedica;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Form\Type\RecursoHumano\ExamenType;
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

class ExamenController extends AbstractController
{
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
                        $arExamen->setCargoRel($arEmpleado->getCargoRel());
                        $arExamen->setCodigoSexoFk($arEmpleado->getCodigoSexoFk());
                        if ($id == 0) {
                            $arExamen->setFecha(new \DateTime('now'));
                            $arExamen->setUsuario($this->getUser()->getUserName());
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
        $arExamenes = $em->getRepository(RhuExamen::class)->find($id);
        $form = Estandares::botonera($arExamenes->getEstadoAutorizado(), $arExamenes->getEstadoAprobado(), $arExamenes->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnApto = ['label' => 'Apto', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBotonAprobarDetalle = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];

        if ($arExamenes->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
            $arrBtnApto['disabled'] = false;
            $arrBtnApto['disabled'] = false;
            $arrBotonAprobarDetalle['disabled'] = false;
        }
        if ($arExamenes->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
            $arrBtnApto['disabled'] = true;
            $arrBotonAprobarDetalle['disabled'] = true;
        }

        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->add('btnApto', SubmitType::class, $arrBtnApto);
        $form->add('btnAprobarDetalle', SubmitType::class, $arrBotonAprobarDetalle);
        $form->add('btnExcel', SubmitType::class, array('label' => 'Excel'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(RhuExamenDetalle::class)->eliminar($arrDetallesSeleccionados, $id);
                $em->getRepository(RhuExamen::class)->liquidar($id);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(RhuExamenDetalle::class)->actualizarDetalles($arrControles, $form, $arExamenes);
            }
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuExamen::class)->autorizar($arExamenes);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuExamen::class)->desAutorizar($arExamenes);
            }
            if ($form->get('btnApto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuExamenDetalle::class)->apto($arExamenes, $arrSeleccionados);
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
            return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_detalle', ['id' => $id]));
        }
        $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $id));
        $arExamenRestriccionesMedicas = $em->getRepository(RhuExamenRestriccionMedica::class)->findBy(array('codigoExamenFk' => $id));
        return $this->render('recursohumano/movimiento/contratacion/examen/detalle.html.twig', array(
            'form' => $form->createView(),
            'arExamen' => $arExamenes,
            'arExamenDetalles' => $arExamenDetalles,
            'arExamenRestriccionesMedicas'=>$arExamenRestriccionesMedicas
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
        $arExamenListaPrecios = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoExamenEntidadFk' => $arExamen->getCodigoExamenEntidadFk()));
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
                            $arExamenDetalleValidar = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $codigoExamen, 'codigoExamenTipoFk' => $arExamenListaPrecio->getCodigoExamenTipoFk()));
                            if (!$arExamenDetalleValidar) {
                                $arExamenTipo = $em->getRepository(RhuExamenTipo::class)->find($arExamenListaPrecio->getCodigoExamenTipoFk());
                                $arExamenDetalle = new RhuExamenDetalle();
                                $arExamenDetalle->setExamenTipoRel($arExamenTipo);
                                $arExamenDetalle->setExamenRel($arExamen);
                                $arExamenDetalle->setFechaExamen($arExamen->getFecha());
                                $arExamenDetalle->setFechaVence($arExamen->getFecha());
                                $arExamenDetalle->setVrPrecio($arExamenListaPrecio->getVrPrecio());
                                $em->persist($arExamenDetalle);
                            }
                        }
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    } else {
                        Mensajes::error("error", "No selecciono ningun dato para guardar");
                    }
                } else {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arExamenListaPrecios = $paginator->paginate($arExamenListaPrecios, $request->query->get('page', 1), 50);
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

    public function exportarExcelPersonalizado($arExamenDetalles,$ExamenRestriccionesMedicas)
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
            $hoja->setCellValue('C1', 'PRECIO');
            $hoja->setCellValue('D1', 'FECHA EXAMEN');
            $hoja->setCellValue('E1', 'VENCIMIENTO');
            $hoja->setCellValue('F1', 'VALIDA VENCIMIENTO');
            $hoja->setCellValue('G1', 'APROBADO');
            $hoja->setCellValue('H1', 'CERRADO');
            $hoja->setCellValue('I1', 'COMENTARIOS');
            $hoja->setCellValue('J1', 'APTO');

            $i = 2;
            foreach ($arExamenDetalles as $arExamenDetalle) {
                $hoja->getStyle($i)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $i, $arExamenDetalle->getCodigoExamenDetallePk())
                    ->setCellValue('B' . $i, $arExamenDetalle->getExamenTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arExamenDetalle->getVrPrecio())
                    ->setCellValue('D' . $i, $arExamenDetalle->getFechaExamen())
                    ->setCellValue('E' . $i, $arExamenDetalle->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arExamenDetalle->getValidarVencimiento() == 0 ? "No" : "Si")
                    ->setCellValue('G' . $i, $arExamenDetalle->getEstadoAprobado() == 0 ? "No" : "Si")
//                ->setCellValue('H' . $i, $arExamenDetalle->getEstadoCerrado() == 0 ? "No" : "Si")
                    ->setCellValue('H' . $i, "")
                    ->setCellValue('I' . $i, $arExamenDetalle->getComentario())
                    ->setCellValue('J' . $i, $arExamenDetalle->getEstadoApto() == 0 ? "No" : "Si");
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

}

