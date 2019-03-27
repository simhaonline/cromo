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

class ContactoController extends ControllerListenerGeneral
{
    protected $class= InvTercero::class;
    protected $claseNombre = "InvContacto";
    protected $modulo = "Inventario";
    protected $funcion = "Administracion";
    protected $grupo = "General";
    protected $nombre = "Contacto";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/administracion/general/contacto/lista",name="inventario_administracion_general_contacto_lista")
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
//            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-danger btn-sm', 'style' => 'float:right']])
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
//            if ($form->get('btnEliminar')->isClicked()) {
//                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                foreach ($arrSeleccionados as $codigoTercero) {
//                    $arTercero = $em->getRepository(InvTercero::class)->find($codigoTercero);
//                    if ($arTercero) {
//                        $em->remove($arTercero);
//                    }
//                }
//                try {
//                    $em->flush();
//                } catch (\Exception $e) {
//                    Mensajes::error('No se puede eliminar, el tercero esta siendo utilizado en el sistema.');
//                }
//            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->createQuery($em->getRepository(InvContacto::class)->lista(null))->execute(), "Contactos");
            }
        }
        $arContactos = $paginator->paginate($em->getRepository(InvContacto::class)->lista(null), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/administracion/general/contacto/lista.html.twig', [
            'arContactos' => $arContactos,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/contacto/detalle/{id}",name="inventario_administracion_general_contacto_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $arContacto = $em->getRepository(InvContacto::class)->find($id);
        return $this->render('inventario/administracion/general/contacto/detalle.html.twig', [
            'arContacto' => $arContacto
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/general/contacto/nuevo/{id}",name="inventario_administracion_general_contacto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContacto = new InvContacto();
        if ($id != 0) {
            $arContacto = $em->getRepository(InvContacto::class)->find($id);
        }
        $form = $this->createForm(ContactoType::class, $arContacto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(InvTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arContacto->setTerceroRel($arTercero);
                        $em->persist($arContacto);
                        $em->flush();
                        return $this->redirect($this->generateUrl('inventario_administracion_general_contacto_detalle', ['id' => $arContacto->getCodigoContactoPk()]));
                    }
                }
            } else {
                Mensajes::error('Debes seleccionar un tercero');
            }
        }
        return $this->render('inventario/administracion/general/contacto/nuevo.html.twig', [
            'arContacto' => $arContacto,
            'form' => $form->createView()
        ]);
    }
}