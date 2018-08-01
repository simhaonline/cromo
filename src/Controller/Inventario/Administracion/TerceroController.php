<?php

namespace App\Controller\Inventario\Administracion;

use App\Entity\Inventario\InvTercero;
use App\Form\Type\Inventario\TerceroType;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TerceroController extends Controller
{
    /**
     * @Route("/inventario/administracion/general/tercero/lista",name="inventario_administracion_general_tercero_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigo',TextType::class,['required' => false,'data' => $session->get('filtroInvTerceroCodigo'),'attr' => ['class' => 'form-control']])
            ->add('txtNombre',TextType::class,['required' => false,'data' => $session->get('filtroInvTerceroNombre'),'attr' => ['class' => 'form-control','readonly' => 'readonly']])
            ->add('btnFiltrar',SubmitType::class,['label' => 'Filtrar','attr' => ['class' => 'btn btn-default btn-sm']])
            ->add('btnEliminar',SubmitType::class,['label' => 'Eliminar','attr' => ['class' => 'btn btn-danger btn-sm', 'style' => 'float:right']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvTerceroCodigo',$form->get('txtCodigo')->getData());
                if($session->get('filtroInvTerceroCodigo') != ''){
                    $session->set('filtroInvTerceroNombre',$form->get('txtNombre')->getData());
                } else {
                    $session->set('filtroInvTerceroNombre','');
                }
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoTercero){
                    $arTercero = $em->getRepository(InvTercero::class)->find($codigoTercero);
                    if($arTercero){
                        $em->remove($arTercero);
                    }
                }
                try{
                    $em->flush();
                }catch (\Exception $e){
                    Mensajes::error('No se puede eliminar, el tercero esta siendo utilizado en el sistema.');
                }
            }
        }
        $arTerceros = $paginator->paginate($em->getRepository(InvTercero::class)->lista(1), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/administracion/general/tercero/lista.html.twig', [
            'arTerceros' => $arTerceros,
            'form' => $form->createView()
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
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arTercero);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_general_tercero_lista'));
            }
        }
        return $this->render('inventario/administracion/general/tercero/nuevo.html.twig', [
            'arTercero' => $arTercero,
            'form' => $form->createView()
        ]);
    }
}
