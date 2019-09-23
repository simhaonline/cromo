<?php

namespace App\Controller\RecursoHumano\Movimiento\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenClase;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoExamenClaseFk', EntityType::class, [
                'class' => RhuExamenClase::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.codigoExamenClasePk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoExamenPk', TextType::class, array('required' => false))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
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
                return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_lista'));
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
        if ($arExamenes->getEstadoAutorizado()) {
            $arrBtnAutorizar['disable'] = true;
            $arrBtnEliminar['disable'] = true;
            $arrBtnActualizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
            $arrBtnDesautorizar['disable'] = false;
        }
        if ($arExamenes->getEstadoAprobado()) {
            $arrBtnDesautorizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
        }

        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
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
            return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_examen_detalle', ['id' => $id]));
        }
        $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $id));
        return $this->render('recursohumano/movimiento/contratacion/examen/detalle.html.twig', array(
            'form' => $form->createView(),
            'arExamenDetalles' => $arExamenDetalles,
            'arExamenes' => $arExamenes
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
        $arExamenListaPrecios = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoEntidadExamenFk' => $arExamen->getCodigoEntidadExamenFk()));
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
        $filtro = [
            'codigoExamen' => $form->get('codigoExamenPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arExamenClase = $form->get('codigoExamenClaseFk')->getData();

        if (is_object($arExamenClase)) {
            $filtro['examenClase'] = $arExamenClase->getCodigoExamenClasePk();
        } else {
            $filtro['examenClase'] = $arExamenClase;
        }

        return $filtro;

    }
}

