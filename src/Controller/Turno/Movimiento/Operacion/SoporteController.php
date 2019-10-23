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
        $form = $this->createFormBuilder()
            ->add('codigoSoportePk', TextType::class, array('required' => false))
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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

        if ($form->isSubmitted()) {

            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }

            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TurSoporte::class)->lista($raw)->getQuery()->execute(), "Soporte");

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
        }
        $form = $this->createForm(SoporteType::class, $arSoporte);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arSoporte->setUsuario($this->getUser()->getUserName());
                }
                $arSoporte->setDias(($arSoporte->getFechaDesde()->diff($arSoporte->getFechaHasta()))->days + 1);
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
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arSoporte = $em->getRepository(TurSoporte::class)->find($id);
        $form = Estandares::botonera($arSoporte->getEstadoAutorizado(), $arSoporte->getEstadoAprobado(), $arSoporte->getEstadoAnulado());

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
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TurSoporte::class)->autorizar($arSoporte);
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(TurSoporte::class)->desAutorizar($arSoporte);
            }
            if($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TurSoporte::class)->aprobar($arSoporte);
            }
            if($form->get('btnCargarContratos')->isClicked()) {
                $em->getRepository(TurSoporte::class)->cargarContratos($arSoporte);
            }
            if ($form->get('btnEliminarDetalle')->isClicked()) {
                $arrDetalles = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TurSoporteContrato::class)->retirarDetalle($arrDetalles);
            }
            return $this->redirect($this->generateUrl('turno_movimiento_operacion_soporte_detalle', ['id' => $id]));
        }
        $arSoporteContratos = $paginator->paginate($em->getRepository(TurSoporteContrato::class)->lista($id), $request->query->getInt('page', 1), 1000);

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

                $arSoporteContrato = $em->getRepository(TurSoporteContrato::class)->find($id);
                if($arSoporteContrato->getCodigoDistribucionFk()) {
                    $em->getRepository(TurSoporteContrato::class)->distribucion($arSoporteContrato);
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
        return $filtro = [
            'codigoSoportePk' => $form->get('codigoSoportePk')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),

        ];


        return $filtro;
    }

}
