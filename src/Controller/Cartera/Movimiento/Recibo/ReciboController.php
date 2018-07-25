<?php

namespace App\Controller\Cartera\Movimiento\Recibo;

use App\Entity\Cartera\CarRecibo;
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
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/recibo/recibo/lista", name="cartera_movimiento_recibo_recibo_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNit', NumberType::class, ['label' => 'Nit: ', 'required' => false, 'data' => $session->get('filtroNitCliente')])
            ->add('txtCliente', TextType::class, ['label' => 'Cliente: ', 'required' => false, 'data' => $session->get('filtroNombreCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroNitCliente', $form->get('txtNit')->getData());
            $session->set('filtroNombreCliente', $form->get('txtCliente')->getData());
        }
        $arRecibo = $paginator->paginate($em->getRepository(CarRecibo::class)->lista(), $request->query->getInt('page', 1),20);
        return $this->render('cartera/movimiento/recibo/lista.html.twig',
            ['arRecibo' => $arRecibo,
            'form' => $form->createView()]);
    }

//    /**
//     * @Route("/transporte/administracion/comercial/cliente/nuevo/{id}", name="transporte_administracion_comercial_cliente_nuevo")
//     */
//    public function nuevo(Request $request, $id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $arCliente = new TteCliente();
//        if ($id != '0') {
//            $arCliente = $em->getRepository(TteCliente::class)->find($id);
//            if (!$arCliente) {
//                return $this->redirect($this->generateUrl('transporte_administracion_comercial_cliente_lista'));
//            }
//        }
//        $form = $this->createForm(ClienteType::class, $arCliente);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            if ($form->get('guardar')->isClicked()) {
//                $arCliente->setNombreCorto($arCliente->getNombre1() . " " . $arCliente->getNombre2() . " " . $arCliente->getApellido1() . " " . $arCliente->getApellido2());
//                $em->persist($arCliente);
//                $em->flush();
//                return $this->redirect($this->generateUrl('transporte_administracion_comercial_cliente_detalle', ['id' => $arCliente->getCodigoClientePk()]));
//            }
//        }
//        return $this->render('transporte/administracion/comercial/cliente/nuevo.html.twig', [
//            'arCliente' => $arCliente,
//            'form' => $form->createView()
//        ]);
//    }
//
//    /**
//     * @Route("/transporte/administracion/comercial/cliente/detalle/{id}", name="transporte_administracion_comercial_cliente_detalle")
//     */
//    public function detalle(Request $request, $id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $arCliente = $em->getRepository(TteCliente::class)->find($id);
//        $form = $this->createFormBuilder()
//            ->add('btnEliminarDetalle', SubmitType::class, array('label' => 'Eliminar'))
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->get('btnEliminarDetalle')->isClicked()) {
//            $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                $em->getRepository(TteClienteCondicion::class)->eliminar($arrSeleccionados);
//            }
//
//        $arCondicion = $em->getRepository(TteClienteCondicion::class)->clienteCondicion($id);
//
//        return $this->render('transporte/administracion/comercial/cliente/detalle.html.twig', array(
//            'arCliente' => $arCliente,
//            'arCondiciones' => $arCondicion,
//            'form' => $form->createView()
//        ));
//    }
//
//    /**
//     * @param Request $request
//     * @param $id
//     * @return Response
//     * * @Route("/transporte/administracion/comercial/cliente/detalle/nuevo/{id}", name="transporte_administracion_comercial_cliente_detalle_nuevo")
//     * @throws \Doctrine\ORM\ORMException
//     */
//    public function detalleNuevo(Request $request, $id)
//    {
//        $paginator  = $this->get('knp_paginator');
//        $em = $this->getDoctrine()->getManager();
//        $respuesta = [];
//        $form = $this->createFormBuilder()
//            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $arrSeleccionados = $request->request->get('ChkSeleccionar');
//            if (count($arrSeleccionados) > 0) {
//                foreach ($arrSeleccionados AS $codigo) {
//                   if(!$em->getRepository(TteClienteCondicion::class)->findOneBy(['codigoClienteFk' => $id, 'codigoCondicionFk' => $codigo])){
//                       $arClienteCondicion = new TteClienteCondicion();
//                       $arClienteCondicion->setClienteRel($em->getRepository(TteCliente::class)->find($id));
//                       $arClienteCondicion->setCondicionRel($em->getRepository(TteCondicion::class)->find($codigo));
//                       $em->persist($arClienteCondicion);
//                   } else {
//                       $respuesta [] = "La condición con código {$codigo} ya se encuentra agregada para el cliente seleccionado";
//                   }
//                }
//                $em->flush();
//            }
//            if(count($respuesta) > 0){
//                foreach ($respuesta AS $error){
//                    Mensajes::error($error);
//                }
//            } else {
//            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
//            }
//        }
//        $arCondiciones = $paginator->paginate ($em->getRepository(TteCondicion::class)->lista(), $request->query->getInt('page', 1),30);
//        return $this->render('transporte/administracion/comercial/cliente/detalleNuevo.html.twig', ['arCondiciones' => $arCondiciones, 'form' => $form->createView()]);
//    }
}

