<?php

namespace App\Controller\Inventario\Proceso\Contabilidad;

use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class MovimientoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/proceso/contabilidad/movimiento/lista", name="inventario_proceso_contabilidad_movimiento_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvCodigoTercero'), 'attr' => ['class' => 'form-control']])
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroInvMovimientoCodigo')))
            ->add('txtNumero', TextType::class, array('data' => $session->get('filtroInvMovimientoNumero')))
            ->add('cboAsesor', EntityType::class, $em->getRepository(GenAsesor::class)->llenarCombo())
            ->add('chkEstadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvMovimientoEstadoAutorizado'), 'required' => false])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvMovimientoEstadoAprobado'), 'required' => false])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnContabilizar', SubmitType::class, array('label' => 'Contabilizar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroFechaDesde',  $form->get('fechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                $session->set('filtroFecha', $form->get('filtrarFecha')->getData());
                if ($form->get('txtCodigoCliente')->getData() != '') {
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                } else {
                    $session->set('filtroTteCodigoCliente', null);
                    $session->set('filtroTteNombreCliente', null);
                }
                $arFacturaTipo = $form->get('cboFacturaTipoRel')->getData();
                if ($arFacturaTipo) {
                    $session->set('filtroTteFacturaCodigoFacturaTipo', $arFacturaTipo->getCodigoFacturaTipoPk());
                } else {
                    $session->set('filtroTteFacturaCodigoFacturaTipo', null);
                }
            }
            if ($form->get('btnContabilizar')->isClicked()) {
                $arr = $request->request->get('ChkSeleccionar');
                $respuesta = $this->getDoctrine()->getRepository(TteFactura::class)->contabilizar($arr);
            }
        }
        $arMovimientos = $paginator->paginate($em->getRepository(InvMovimiento::class)->listaContabilizar(), $request->query->getInt('page', 1), 100);
        return $this->render('transporte/proceso/contabilidad/factura/lista.html.twig',
            ['arMovimientos' => $arMovimientos,
            'form' => $form->createView()]);
    }

}

