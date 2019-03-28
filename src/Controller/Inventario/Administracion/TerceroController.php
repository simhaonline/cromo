<?php

namespace App\Controller\Inventario\Administracion;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvContacto;
use App\Entity\Inventario\InvSucursal;
use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\ContactoType;
use App\Form\Type\Inventario\SucursalType;
use App\Form\Type\Inventario\TerceroType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TerceroController extends ControllerListenerGeneral
{
    protected $class = InvTercero::class;
    protected $claseNombre = "InvTercero";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Tercero";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/administracion/general/tercero/lista",name="inventario_administracion_general_tercero_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, ['required' => false, 'data' => $session->get('filtroInvTerceroCodigo'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombre', TextType::class, ['required' => false, 'data' => $session->get('filtroInvTerceroNombre'), 'attr' => ['class' => 'form-control', 'readonly' => 'readonly']])
            ->add('txtNombreTercero', TextType::class, ['required' => false, 'data' => $session->get('filtroInvNombreTercero'), 'attr' => ['class' => 'form-control']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-default btn-sm']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-danger btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvTerceroCodigo', $form->get('txtCodigo')->getData());
                $session->set('filtroInvNombreTercero', $form->get('txtNombreTercero')->getData());
                if ($session->get('filtroInvTerceroCodigo') != '') {
                    $session->set('filtroInvTerceroNombre', $form->get('txtNombre')->getData());
                } else {
                    $session->set('filtroInvTerceroNombre', '');
                }
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoTercero) {
                    $arTercero = $em->getRepository(InvTercero::class)->find($codigoTercero);
                    if ($arTercero) {
                        $em->remove($arTercero);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el tercero esta siendo utilizado en el sistema.');
                }
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvTercero::class)->lista(null))->execute(), "Terceros");
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(InvTercero::class)->lista(null), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/administracion/general/tercero/lista.html.twig', [
            'arTerceros' => $arTerceros,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/tercero/detalle/{id}",name="inventario_administracion_general_tercero_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arTercero = $em->getRepository(InvTercero::class)->find($id);
        $arSucursales = $this->getDoctrine()->getRepository(InvSucursal::class)->listaSucursal($id)->getQuery()->getResult();
        $arContactos = $this->getDoctrine()->getRepository(InvContacto::class)->listaTercero($id)->getQuery()->getResult();
        return $this->render('inventario/administracion/general/tercero/detalle.html.twig', [
            'arSucursales' => $arSucursales,
            'arContactos' => $arContactos,
            'arTercero' => $arTercero
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/tercero/nuevo/{id}",name="inventario_administracion_general_tercero_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTercero = new InvTercero();
        if ($id != 0) {
            $arTercero = $em->getRepository(InvTercero::class)->find($id);
            if (!$arTercero) {
                return $this->redirect($this->generateUrl('inventario_administracion_general_tercero_lista'));
            }
        }
        $form = $this->createForm(TerceroType::class, $arTercero);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $strRespuesta = "";
            $identificacion = $form->get('numeroIdentificacion')->getData();
            if($id == 0){
                $identificacionExistente = $em->getRepository(InvTercero::class)
                    ->findBy(['numeroIdentificacion' => $identificacion]);
                if (!$identificacionExistente) {
                        $em->persist($arTercero);
                } else {
                    $strRespuesta = "El numero de identificacion ya existe";
                }
            }else{
                $em->persist($arTercero);
            }
            if($strRespuesta == ""){
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_general_tercero_detalle', ['id' => $arTercero->getCodigoTerceroPk()]));
            }else{
                Mensajes::error($strRespuesta);
            }
        }
        return $this->render('inventario/administracion/general/tercero/nuevo.html.twig', [
            'arTercero' => $arTercero,
            'form' => $form->createView()
        ]);
    }


    /**
     * @param Request $request
     * @param $codigoTercero
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/tercero/sucursal/{codigoTercero}/{id}",name="inventario_administracion_general_tercero_sucursal")
     */
    public function nuevoSucursal(Request $request, $codigoTercero, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSucursal = new InvSucursal();
        $arTercero = $em->getRepository(InvTercero::class)->find($codigoTercero);
        if ($id != '0') {
            $arSucursal = $this->getDoctrine()->getManager()->getRepository(InvSucursal::class)->find($id);
        }
        $form = $this->createForm(SucursalType::class, $arSucursal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSucursal->setTerceroRel($arTercero);
                $em->persist($arSucursal);
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('inventario/administracion/general/tercero/nuevoSucursal.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoTercero
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/tercero/contacto/{codigoTercero}/{id}",name="inventario_administracion_general_tercero_contacto")
     */
    public function nuevoContacto(Request $request, $codigoTercero, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContacto = new InvContacto();
        $arTercero = $em->getRepository(InvTercero::class)->find($codigoTercero);
        if ($id != '0') {
            $arContacto = $this->getDoctrine()->getManager()->getRepository(InvContacto::class)->find($id);
        }
        $form = $this->createForm(ContactoType::class, $arContacto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arContacto->setTerceroRel($arTercero);
                $em->persist($arContacto);
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('inventario/administracion/general/tercero/nuevoContacto.html.twig', [
            'form' => $form->createView()
        ]);
    }
}