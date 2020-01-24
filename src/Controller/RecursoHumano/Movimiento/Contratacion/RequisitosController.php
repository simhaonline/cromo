<?php

namespace App\Controller\RecursoHumano\Movimiento\Contratacion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Entity\RecursoHumano\RhuRequisitoDetalle;
use App\Entity\RecursoHumano\RhuRequisitoTipo;
use App\Form\Type\RecursoHumano\ExamenType;
use App\Form\Type\RecursoHumano\RequisitoType;
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RequisitosController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "RhuRequisito";

    protected $clase = RhuRequisito::class;
    protected $claseNombre = "RhuRequisito";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Contratacion";
    protected $nombre = "Requisito";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/contratacion/requisito/lista", name="recursohumano_movimiento_contratacion_requisito_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroRhuRequisito')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoRequisitoTipoFk', EntityType::class, [
                'class' => RhuRequisitoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.codigoRequisitoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2'],
                'data'=>  $raw['filtros']['requisitoTipo'] ? $em->getReference(RhuRequisitoTipo::class, $raw['filtros']['requisitoTipo']) : null
            ])
            ->add('codigoRequisitoPk', TextType::class, array('required' => false, 'data'=>$raw['filtros']['codigoRequisito']))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => false, 'data'=>$raw['filtros']['codigoEmpleado']])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaDesde']?date_create($raw['filtros']['fechaDesde']):null ])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaHasta']?date_create($raw['filtros']['fechaHasta']):null ])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAprobado'] ])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAnulado'] ])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuRequisito::class)->lista($raw), "Creditos");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuRequisito::class)->eliminar($arrSeleccionados);
            }
        }
        $arRequisitos = $paginator->paginate($em->getRepository(RhuRequisito::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/contratacion/requisito/lista.html.twig', [
            'arRequisitos' => $arRequisitos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("recursohumano/movimiento/contratacion/requisito/nuevo/{id}", name="recursohumano_movimiento_contratacion_requisito_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisito = new RhuRequisito();
        if ($id != '0') {
            $arRequisito = $em->getRepository(RhuRequisito::class)->find($id);
            if (!$arRequisito) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_examen_examen_lista'));
            }
        } else {
            $arRequisito->setUsuario($this->getUser()->getUserName());
            $arRequisito->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(RequisitoType::class, $arRequisito);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arRequisito->setUsuario($this->getUser()->getUserName());
                    $arRequisitosConceptos = $em->getRepository(RhuRequisitoConcepto::class)->findBy(array('general' => 1));
                    foreach ($arRequisitosConceptos as $arRequisitoConcepto) {
                        $arRequisitoDetalle = new RhuRequisitoDetalle();
                        $arRequisitoDetalle->setRequisitoRel($arRequisito);
                        $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoConcepto);
                        $arRequisitoDetalle->setTipo('GENERAL');
                        $arRequisitoDetalle->setCantidad(1);
                        $em->persist($arRequisitoDetalle);
                    }
                    $arRequisitosCargos = $em->getRepository(RhuRequisitoCargo::class)->findBy(array('codigoCargoFk' => $form->get('cargoRel')->getData()));
                    foreach ($arRequisitosCargos as $arRequisitoCargo) {
                        $arRequisitoDetalle = new RhuRequisitoDetalle();
                        $arRequisitoDetalle->setRequisitoRel($arRequisito);
                        $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoCargo->getRequisitoConceptoRel());
                        $arRequisitoDetalle->setTipo('CARGO');
                        $arRequisitoDetalle->setCantidad(1);
                        $em->persist($arRequisitoDetalle);
                    }
                }
                $em->persist($arRequisito);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_requisito_detalle', ['id' => $arRequisito->getCodigoRequisitoPk()]));
            }
        }
        return $this->render('recursohumano/movimiento/contratacion/requisito/nuevo.html.twig', [
            'arRequisito' => $arRequisito,
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
     * @Route("/recursohumano/movimiento/contratacion/requisito/detalle/{id}", name="recursohumano_movimiento_contratacion_requisito_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRequisito = $em->getRepository(RhuRequisito::class)->find($id);
        $form = Estandares::botonera($arRequisito->getEstadoAutorizado(), $arRequisito->getEstadoAprobado(), $arRequisito->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDetalleEntregado = ['label' => 'Entregado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arRequisito->getEstadoAutorizado()) {
            $arrBtnDetalleEntregado['disable'] = true;
            $arrBtnAutorizar['disable'] = true;
            $arrBtnEliminar['disable'] = true;
            $arrBtnActualizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
            $arrBtnDesautorizar['disable'] = false;
        }
        if ($arRequisito->getEstadoAprobado()) {
            $arrBtnDesautorizar['disable'] = true;
            $arrBtnAprobado['disable'] = true;
        }
        $form->add('btnDetalleEntregado', SubmitType::class, $arrBtnDetalleEntregado);
        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');

            if ($form->get('BtnDetalleEntregado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(RhuRequisitoDetalle::class)->entregar($arrSeleccionados);
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoRequisitoDetallePk) {
                        $arRequisitoDetalle = new RhuRequisitoDetalle();
                        $arRequisitoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->find($codigoRequisitoDetallePk);
                        if ($arRequisitoDetalle->getEstadoNoAplica() == 0) {
                            if ($arRequisitoDetalle->getEstadoEntregado() == 0) {
                                $arRequisitoDetalle->setEstadoEntregado(1);
                                $arRequisitoDetalle->setCantidadEntregada($arRequisitoDetalle->getCantidad());
                            }
                            $em->persist($arRequisitoDetalle);
                        }
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_requisito_detalle', array('codigoRequisito' => $id)));
            }
            return $this->redirect($this->generateUrl('recursohumano_movimiento_contratacion_requisito_detalle', ['id' => $id]));
        }
        $arRequisitoDetalles = $em->getRepository(RhuRequisitoDetalle::class)->lista($id);
        return $this->render('recursohumano/movimiento/contratacion/requisito/detalle.html.twig', array(
            'form' => $form->createView(),
            'clase' => array('clase' => 'RhuRequisito', 'codigo' => $id),
            'arRequisitoDetalles' => $arRequisitoDetalles,
            'arRequisito' => $arRequisito
        ));
    }

    /**
     * @param Request $request
     * @param $codigoExamen
     * @param int $codigoExamenDetalle
     * @return Response
     * @Route("/recursohumano/movimiento/examen/examen/detalle/nuevo/{codigoExamen}/{id}", name="recursohumano_movimiento_examen_examen_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoExamen)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
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
                            $arExamenListaPrecio = new RhuExamenListaPrecio();
                            $arExamenListaPrecio = $em->getRepository(RhuExamenListaPrecio::class)->find($codigo);
                            $arExamenDetalleValidar = new RhuExamenDetalle();
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
//                        $em->getRepository(RhuExamen::class)->liquidar($codigoExamen);
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
        return $this->render('recursohumano/movimiento/examen/examen/detalleNuevo.html.twig', array(
            'arExamenListaPrecios' => $arExamenListaPrecios,
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoRequisito' => $form->get('codigoRequisitoPk')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];
        $arRequisitoTipo = $form->get('codigoRequisitoTipoFk')->getData();
        if (is_object($arRequisitoTipo)) {
            $filtro['requisitoTipo'] = $arRequisitoTipo->getCodigoRequisitoTipoPk();
        } else {
            $filtro['requisitoTipo'] = $arRequisitoTipo;
        }
        $session->set('filtroRhuRequisito', $filtro);
        return $filtro;
    }
}

