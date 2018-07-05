<?php

namespace App\Controller\Inventario\Movimiento\Compra;

use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvSolicitud;
use App\Entity\Inventario\InvSolicitudDetalle;
use App\Entity\Inventario\InvSolicitudTipo;
use App\Formato\Inventario\Solicitud;
use App\Utilidades\BaseDatos;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\Type\Inventario\SolicitudType;

class SolicitudController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/movimiento/compra/solicitud/lista", name="inventario_movimiento_compra_solicitud_lista")
     * @throws \Doctrine\ORM\ORMException
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtNumero', NumberType::class, ['required' => false, 'data' => $session->get('filtroInvSolicitudNumero')])
            ->add('chkEstadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'data' => $session->get('filtroInvSolicitudEstadoAprobado'), 'required' => false])
            ->add('cboSolicitudTipoRel', EntityType::class, $em->getRepository(InvSolicitudTipo::class)->llenarCombo())
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvSolicitudNumero', $form->get('txtNumero')->getData());
                $session->set('filtroInvSolicitudEstadoAprobado', $form->get('chkEstadoAprobado')->getData());
                $solicitudTipo = $form->get('cboSolicitudTipoRel')->getData();
                if($solicitudTipo != ''){
                    $session->set('filtroInvSolicitudCodigoSolicitudTipo', $form->get('cboSolicitudTipoRel')->getData()->getCodigoSolicitudTipoPk());
                } else {
                    $session->set('filtroInvSolicitudCodigoSolicitudTipo', null);
                }
            }
        }
        $arSolicitudes = $paginator->paginate($em->getRepository(InvSolicitud::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/solicitud/lista.html.twig', [
            'arSolicitudes' => $arSolicitudes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inventario/movimiento/compra/solicitud/nuevo/{id}", name="inventario_movimiento_compra_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new InvSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository(InvSolicitud::class)->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_lista'));
            }
        }
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if($id == 0){
                    $arSolicitud->setFecha(new \DateTime('now'));
                }
                $arSolicitud->setUsuario($this->getUser()->getUserName());
                $em->persist($arSolicitud);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $arSolicitud->getCodigoSolicitudPk()]));
            }
        }
        return $this->render('inventario/movimiento/compra/solicitud/nuevo.html.twig', [
            'arSolicitud' => $arSolicitud,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/inventario/movimiento/compra/solicitud/detalle/{id}", name="inventario_movimiento_compra_solicitud_detalle")
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = $em->getRepository(InvSolicitud::class)->find($id);
        $paginator = $this->get('knp_paginator');
        $form = Estandares::botonera($arSolicitud->getEstadoAutorizado(),$arSolicitud->getEstadoAprobado(),$arSolicitud->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(InvSolicitud::class)->autorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(InvSolicitud::class)->desautorizar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $objFormatoSolicitud = new Solicitud();
                $objFormatoSolicitud->Generar($em, $id);
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(InvSolicitud::class)->aprobar($arSolicitud);
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(InvSolicitud::class)->anular($arSolicitud);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
                return $this->redirect($this->generateUrl('inventario_movimiento_compra_solicitud_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvSolicitudDetalle::class)->eliminar($arSolicitud, $arrDetallesSeleccionados);
                return $this->redirect($this->generateUrl('inventario_movimiento_inventario_solicitud_detalle', ['id' => $id]));
            }
        }
        $arSolicitudDetalles = $paginator->paginate($em->getRepository(InvSolicitudDetalle::class)->lista($arSolicitud->getCodigoSolicitudPk()), $request->query->getInt('page', 1), 40);
        return $this->render('inventario/movimiento/compra/solicitud/detal  le.html.twig', [
            'arSolicitudDetalles' => $arSolicitudDetalles,
            'arSolicitud' => $arSolicitud,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/inventario/movimiento/compra/solicitud/detalle/nuevo/{id}", name="inventario_movimiento_compra_solicitud_detalle_nuevo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detalleNuevo(Request $request, $id)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $arSolicitud = $em->getRepository(InvSolicitud::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('txtCodigoItem', TextType::class, ['label' => 'Codigo: ', 'required' => false])
            ->add('txtNombreItem', TextType::class, ['label' => 'Nombre: ', 'required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvItemCodigo', $form->get('txtCodigoItem')->getData());
                $session->set('filtroInvItemNombre', $form->get('txtNombreItem')->getData());
            }
            if ($form->get('btnGuardar')->isClicked()) {
                $arrItems = $request->request->get('itemCantidad');
                if (count($arrItems) > 0) {
                    foreach ($arrItems as $codigoItem => $cantidad) {
                        if ($cantidad != '' && $cantidad != 0) {
                            $arItem = $em->getRepository(InvItem::class)->find($codigoItem);
                            $arSolicitudDetalle = new InvSolicitudDetalle();
                            $arSolicitudDetalle->setSolicitudRel($arSolicitud);
                            $arSolicitudDetalle->setItemRel($arItem);
                            $arSolicitudDetalle->setCantidad($cantidad);
                            $arSolicitudDetalle->setCantidadPendiente($cantidad);
                            $em->persist($arSolicitudDetalle);
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->lista(), $request->query->getInt('page', 1), 10);
        return $this->render('inventario/movimiento/compra/solicitud/detalleNuevo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }
}
