<?php

namespace App\Controller\Turno\Movimiento\Juridico;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConfiguracion;
use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Form\Type\Turno\ContratoType;
use App\Form\Type\Turno\ContratoDetalleType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContratoController extends AbstractController
{
    protected $clase = TurContrato::class;
    protected $claseNombre = "TurContrato";
    protected $modulo = "Turno";
    protected $funcion = "Movimiento";
    protected $grupo = "Juridico";
    protected $nombre = "Contrato";


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/turno/movimiento/juridico/contrato/lista", name="turno_movimiento_juridico_contrato_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoContratoPk', TextType::class, array('required' => false))
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoCerrado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SIN CERRAR' => '0', 'CERRADO' =>  '1'], 'required' => false])
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted() ) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurContrato::class)->lista($raw)->getQuery()->execute(), "Contratos");

            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurContrato::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_lista'));
            }
        }
        $arContratos = $paginator->paginate($em->getRepository(TurContrato::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('turno/movimiento/juridico/contrato/lista.html.twig', [
            'arContratos' => $arContratos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/turno/movimiento/juridico/contrato/nuevo/{id}", name="turno_movimiento_juridico_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = new TurContrato();
        if ($id != '0') {
            $arContrato = $em->getRepository(TurContrato::class)->find($id);
            if (!$arContrato) {
                return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_lista'));
            }
        } else {
            $arrConfiguracion = $em->getRepository(TurConfiguracion::class)->comercialNuevo();
            $arContrato->setVrSalarioBase($arrConfiguracion['vrSalarioMinimo']);
            $arContrato->setUsuario($this->getUser()->getUserName());
            $arContrato->setEstrato(6);

        }
        $form = $this->createForm(ContratoType::class, $arContrato);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TurCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        $arContrato->setClienteRel($arCliente);
                        if ($id == 0) {
                            $nuevafecha = date('Y/m/', strtotime('-1 month', strtotime(date('Y/m/j'))));
                            $dateFechaGeneracion = date_create($nuevafecha . '01');
                            $arContrato->setFechaGeneracion($dateFechaGeneracion);
                            $arContrato->setUsuario($this->getUser()->getUserName());
                        }
                        $em->persist($arContrato);
                        $em->flush();
                        return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle', ['id' => $arContrato->getCodigoContratoPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un cliente');
                }

            }
        }
        return $this->render('turno/movimiento/juridico/contrato/nuevo.html.twig', [
            'arContrato' => $arContrato,
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
     * @Route("/turno/movimiento/juridico/contrato/detalle/{id}", name="turno_movimiento_juridico_contrato_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(TurContrato::class)->find($id);
        $form = Estandares::botonera($arContrato->getEstadoAutorizado(), $arContrato->getEstadoAprobado(), $arContrato->getEstadoAnulado());

        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnLiquidar = ['label' => 'Liquidar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAutorizar = ['label' => 'Autorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAprobado = ['label' => 'Aprobado', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnCerrar = ['label' => 'Cerrar', 'disabled' => true, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnCerrarDetalle = ['label' => 'Cerrar detalle', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAbrirDetalle = ['label' => 'Abrir detalle', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnDesautorizar = ['label' => 'Desautorizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arContrato->getEstadoAutorizado()) {
            $arrBtnAutorizar['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnLiquidar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
            $arrBtnCerrar['disabled'] = false;
            $arrBtnCerrarDetalle['disabled'] = true;
            $arrBtnAbrirDetalle['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = false;
        }
        if ($arContrato->getEstadoAprobado()) {
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
            $arrBtnCerrarDetalle['disabled'] = true;
            $arrBtnAbrirDetalle['disabled'] = true;
        }
        if ($arContrato->getEstadoCerrado()) {
            $arrBtnCerrar['disabled'] = true;
            $arrBtnDesautorizar['disabled'] = true;
            $arrBtnAprobado['disabled'] = true;
        }
        $form->add('btnCerrar', SubmitType::class, $arrBtnCerrar);
        $form->add('btnCerrarDetalle', SubmitType::class, $arrBtnCerrarDetalle);
        $form->add('btnAbrirDetalle', SubmitType::class, $arrBtnAbrirDetalle);
        $form->add('btnActualizar', SubmitType::class, $arrBtnActualizar);
        //$form->add('btnLiquidar', SubmitType::class, $arrBtnLiquidar);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            /*if ($form->get('btnLiquidar')->isClicked()) {
                $em->getRepository(TurContrato::class)->liquidar($arContrato);
            }*/

            if ($form->get('btnAutorizar')->isClicked()) {
                //$em->getRepository(TurContrato::class)->actualizarDetalles($id, $arrControles);
                $em->getRepository(TurContrato::class)->autorizar($arContrato);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurContrato::class)->desautorizar($arContrato);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                //$objFormatopedido = new Pedido();
                //$objFormatopedido->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                //$em->getRepository(InvPedido::class)->aprobar($arPedido);
            }
            if ($form->get('btnAnular')->isClicked()) {
                //$em->getRepository(InvPedido::class)->anular($arPedido);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $em->getRepository(TurContratoDetalle::class)->eliminar($arrDetallesSeleccionados, $id);
                $em->getRepository(TurContrato::class)->liquidar($arContrato);
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TurContratoDetalle::class)->actualizarDetalles($arrControles, $form, $arContrato);
                $em->getRepository(TurContrato::class)->liquidar($arContrato);
            }
            if ($form->get('btnCerrarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurContratoDetalle::class)->cerrarSeleccionados($arrSeleccionados);
                $em->getRepository(TurContrato::class)->liquidar($arContrato);
                return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle', ['id' => $id]));
            }
            if ($form->get('btnAbrirDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurContratoDetalle::class)->abrirSeleccionados($arrSeleccionados);
                $em->getRepository(TurContrato::class)->liquidar($arContrato);
                return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle', ['id' => $id]));
            }
            if ($form->get('btnCerrar')->isClicked()) {
                if ($arContrato->getEstadoAutorizado() == 1) {
                    $arContrato->setEstadoAprobado(1);
                    $arContrato->setEstadoCerrado(1);
                    $arContrato->setCodigoUsuarioCierre($this->getUser()->getUsername());
                    $arContrato->setFechaCierre(new \DateTime('now'));

                    $em->persist($arContrato);
                    $em->flush();
                    return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle', ['id' => $id]));
                }
            }

            return $this->redirect($this->generateUrl('turno_movimiento_juridico_contrato_detalle', ['id' => $id]));
        }
        $arContratoDetalles = $paginator->paginate($em->getRepository(TurContratoDetalle::class)->lista($id), $request->query->getInt('page', 1), 10);
        $arContratoDetallesCerrados = $paginator->paginate($em->getRepository(TurContratoDetalle::class)->cerrado($id), $request->query->getInt('page', 1), 10);
        return $this->render('turno/movimiento/juridico/contrato/detalle.html.twig', array(
            'form' => $form->createView(),
            'arContratoDetalles' => $arContratoDetalles,
            'arContratoDetallesCerrados' => $arContratoDetallesCerrados,
            'arContrato' => $arContrato
        ));
    }

    /**
     * @param Request $request
     * @param $codigoContrato
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/turno/movimiento/juridico/contrato/detalle/nuevo/{codigoContrato}/{id}", name="turno_movimiento_juridico_contrato_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoContrato, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $arContratoDetalle = new TurContratoDetalle();
        $arContrato = $em->getRepository(TurContrato::class)->find($codigoContrato);
        if ($id != '0') {
            $arContratoDetalle = $em->getRepository(TurContratoDetalle::class)->find($id);
        } else {
            $arContratoDetalle->setContratoRel($arContrato);
            $arContratoDetalle->setLunes(true);
            $arContratoDetalle->setMartes(true);
            $arContratoDetalle->setMiercoles(true);
            $arContratoDetalle->setJueves(true);
            $arContratoDetalle->setViernes(true);
            $arContratoDetalle->setSabado(true);
            $arContratoDetalle->setDomingo(true);
            $arContratoDetalle->setFestivo(true);
            $arContratoDetalle->setCantidad(1);
            $arContratoDetalle->setFechaDesde(new \DateTime('now'));
            $arContratoDetalle->setFechaHasta(new \DateTime('now'));
            $arContratoDetalle->setVrSalarioBase($arContrato->getVrSalarioBase());
            $arContratoDetalle->setPeriodo('M');
        }
        $form = $this->createForm(ContratoDetalleType::class, $arContratoDetalle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arContratoDetalle->setPorcentajeIva($arContratoDetalle->getConceptoRel()->getPorcentajeIva());
                }
                $em->persist($arContratoDetalle);
                $em->flush();
                $em->getRepository(TurContrato::class)->liquidar($arContrato);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('turno/movimiento/juridico/contrato/detalleNuevo.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        return $filtro = [
            'codigoContratoPk' => $form->get('codigoContratoPk')->getData(),
            'codigoClienteFk' => $form->get('codigoClienteFk')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoCerrado' => $form->get('estadoCerrado')->getData(),
        ];

        return $filtro;
    }
}

