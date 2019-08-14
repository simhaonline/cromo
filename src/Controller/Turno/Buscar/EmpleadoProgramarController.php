<?php

namespace App\Controller\Turno\Buscar;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurPedido;
use App\Entity\Turno\TurPedidoDetalle;
use App\Entity\Turno\TurProgramacion;
use App\Entity\Turno\TurPrototipo;
use App\Entity\Turno\TurPuesto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpleadoProgramarController extends Controller
{
    /**
     * @Route("/turno/buscar/empleadoprogramar/{codigoContratoDetalle}", name="turno_buscar_empleadoprogramar")
     */
    public function lista(Request $request, $codigoContratoDetalle = null)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arContratoDetalle = $em->getRepository(TurContratoDetalle::class)->find($codigoContratoDetalle);
        $formFiltro = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurEmpleadoNombre')))
            ->add('txtCodigo', TextType::class, array('required' => false, 'data' => $session->get('filtroTurEmpleadoCodigo')))
            ->add('txtIdentificacion', TextType::class, array('required' => false, 'data' => $session->get('filtroTurEmpleadoIdentificacion')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurEmpleadoCodigo', $formFiltro->get('txtCodigo')->getData());
                $session->set('filtroTurEmpleadoNombre', $formFiltro->get('txtNombre')->getData());
                $session->set('filtroTurEmpleadoIdentificacion', $formFiltro->get('txtIdentificacion')->getData());
            }

            if ($formFiltro->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigo) {
                        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigo);
                        $arPrototipo = new TurPrototipo();
                        $arPrototipo->setEmpleadoRel($arEmpleado);
                        $arPrototipo->setContratoDetalleRel($arContratoDetalle);
                        $arPrototipo->setFechaInicioSecuencia(new \DateTime('now'));
                        $arPrototipo->setInicioSecuencia(0);
                        $em->persist($arPrototipo);
                    }
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arEmpleados = $paginator->paginate($em->getRepository(RhuEmpleado::class)->listaBuscarTurno(), $request->query->get('page', 1), 20);
        return $this->render('turno/buscar/empleadoProgramar.html.twig', [
            'arEmpleados' => $arEmpleados,
            'form' => $formFiltro->createView()
        ]);
    }

    /**
     * @Route("/turno/buscar/{codigoPedidoDestelle}", name="turno_buscar_empleadopedido")
     */
    public function pedidoLista(Request $request, $codigoPedidoDestelle)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $formFiltro = $this->createFormBuilder()
            ->add('txtNombre', TextType::class, array('required' => false, 'data' => $session->get('filtroTurEmpleadoNombre')))
            ->add('txtCodigo', TextType::class, array('required' => false, 'data' => $session->get('filtroTurEmpleadoCodigo')))
            ->add('txtIdentificacion', TextType::class, array('required' => false, 'data' => $session->get('filtroTurEmpleadoIdentificacion')))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $formFiltro->handleRequest($request);
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltrar')->isClicked()) {
                $session->set('filtroTurEmpleadoCodigo', $formFiltro->get('txtCodigo')->getData());
                $session->set('filtroTurEmpleadoNombre', $formFiltro->get('txtNombre')->getData());
                $session->set('filtroTurEmpleadoIdentificacion', $formFiltro->get('txtIdentificacion')->getData());
            }

            if ($formFiltro->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arPedidoDetalle = $em->getRepository( TurPedidoDetalle::class)->find($codigoPedidoDestelle);

                if(is_array($arrSeleccionados)) {
                    foreach ($arrSeleccionados as $codigo) {
                        $fechaActual = new \DateTime('now');
                        $mesActual =$fechaActual->format('m');
                        $anioActual =$fechaActual->format('y');
                        $arTurProgramacion = new TurProgramacion();
                        $arTurProgramacion->setPedidoRel($arPedidoDetalle->getPedidoRel());
                        $arTurProgramacion->setPedidoDetalleRel($arPedidoDetalle);
                        $arTurProgramacion->setAnio($anioActual);
                        $arTurProgramacion->setMes($mesActual);
                        $arTurProgramacion->setEmpleadoRel($em->getRepository(RhuEmpleado::class)->find($codigo));
                        $arTurProgramacion->setPuestoRel($em->getRepository(TurPuesto::class)->find($arPedidoDetalle->getCodigoPuestoFk()));
                        $em->persist($arTurProgramacion);
                    }
                    $em->flush();
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        //        $arEmpleados = $em->getRepository(RhuEmpleado::class)->findBy(['codigoEmpleadoTipoFk'=>2]);

        $arEmpleados = $paginator->paginate($em->getRepository(RhuEmpleado::class)->findAll(), $request->query->get('page', 1), 20);

        return $this->render('turno/buscar/empleadoProgramacion.html.twig', [
            'arEmpleados' => $arEmpleados,
            'form' => $formFiltro->createView()
        ]);
    }
}

