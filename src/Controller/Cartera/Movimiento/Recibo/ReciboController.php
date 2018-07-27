<?php

namespace App\Controller\Cartera\Movimiento\Recibo;

use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarRecibo;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Cartera\ReciboType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboController extends Controller
{
    /**
     * @Route("/cartera/movimiento/recibo/recibo/lista", name="cartera_movimiento_recibo_recibo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumero', NumberType::class, ['label' => 'Numero: ', 'required' => false, 'data' => $session->get('filtroNumero')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNumero', $form->get('txtNumero')->getData());
        }
        $arRecibo = $paginator->paginate($em->getRepository(CarRecibo::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('cartera/movimiento/recibo/lista.html.twig',
            ['arRecibo' => $arRecibo,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/cartera/movimiento/recibo/recibo/nuevo/{id}", name="cartera_movimiento_recibo_recibo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = new CarRecibo();
        if ($id != '0') {
            $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
            if (!$arRecibo) {
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_lista'));
            }
        }
        $arRecibo->setFecha(new \DateTime('now'));
        $arRecibo->setFechaPago(new \DateTime('now'));
        $form = $this->createForm(ReciboType::class, $arRecibo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arRecibo);
                $em->flush();
                return $this->redirect($this->generateUrl('cartera_movimiento_recibo_recibo_detalle', ['id' => $arRecibo->getCodigoReciboPk()]));
            }
        }
        return $this->render('cartera/movimiento/recibo/nuevo.html.twig', [
            'arRecibo' => $arRecibo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cartera/movimiento/recibo/recibo/detalle/{id}", name="cartera_movimiento_recibo_recibo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        $arReciboDetalle = $em->getRepository(CarReciboDetalle::class)->findBy(array('codigoReciboFk' => $id));

        return $this->render('cartera/movimiento/recibo/detalle.html.twig', array(
            'arRecibo'=> $arRecibo,
            'arReciboDetalle'=> $arReciboDetalle,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * * @Route("/cartera/movimiento/recibo/recibo/detalle/nuevo/{id}", name="cartera_movimiento_recibo_recibo_detalle_nuevo")
     * @throws \Doctrine\ORM\ORMException
     */
    public function detalleNuevo(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arRecibo = $em->getRepository(CarRecibo::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados AS $codigoCuentaCobrar) {
                        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($codigoCuentaCobrar);
                        $vrPago = $em->getRepository(CarReciboDetalle::class)->vrPagoRecibo($codigoCuentaCobrar, $id);
                        $saldo = $arrControles['TxtSaldo' . $codigoCuentaCobrar] - $vrPago;
                        $arReciboDetalle = new CarReciboDetalle();
                        $arReciboDetalle->setReciboRel($arRecibo);
                        $arReciboDetalle->setCuentaCobrarRel($arCuentaCobrar);
                        $arReciboDetalle->setVrPago($saldo);
                        $arReciboDetalle->setNumeroFactura($arCuentaCobrar->getNumeroDocumento());
                        $arReciboDetalle->setCuentaCobrarTipoRel($arCuentaCobrar->getCuentaCobrarTipoRel());
                        $arReciboDetalle->setOperacion(1);
                        $em->persist($arReciboDetalle);
                    }
                    $em->flush();
                }
                $em->getRepository(CarReciboDetalle::class)->liquidar($id);
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        $arCuentasCobrar = $em->getRepository(CarCuentaCobrar::class)->cuentasCobrar($arRecibo->getCodigoClienteFk());
        $arCuentasCobrar = $paginator->paginate($arCuentasCobrar, $request->query->get('page', 1), 50);
        return $this->render('cartera/movimiento/recibo/detalleNuevo.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'arRecibo' => $arRecibo,
            'form' => $form->createView()));
    }
}

