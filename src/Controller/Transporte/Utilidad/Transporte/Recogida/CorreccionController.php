<?php

namespace App\Controller\Transporte\Utilidad\Transporte\Recogida;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenConfiguracion;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaCarga;
use App\Entity\Transporte\TteRecogida;
use App\Form\Type\Transporte\GuiaCorreccionType;
use App\Form\Type\Transporte\RecogidaCorreccionType;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CorreccionController extends ControllerListenerGeneral
{
    protected $proceso = "0012";
    protected $procestoTipo = "U";
    protected $nombreProceso = "CorreccionRecogidas";
    protected $modulo = "Transporte";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/transporte/utilidad/transporte/recogida/correccion/lista", name="transporte_utilidad_transporte_recogida_correccion_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigo', TextType::class, array('data' => $session->get('filtroTteRecogidaCodigo')))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session = new session;
                $session->set('filtroTteRecogidaCodigo', $form->get('txtCodigo')->getData());
            }
        }
        $arRecogidas = $paginator->paginate($arGuias = $em->getRepository(TteRecogida::class)->correccion(), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/utilidad/transporte/recogida/correccion/lista.html.twig', [
            'arRecogidas' => $arRecogidas,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/transporte/utilidad/transporte/recogida/correccion/nuevo/{id}", name="transporte_utilidad_transporte_recogida_correccion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRecogida = $em->getRepository(TteRecogida::class)->find($id);
        $form = $this->createForm(RecogidaCorreccionType::class, $arRecogida);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $em->persist($arRecogida);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('transporte_utilidad_transporte_recogida_correccion_lista'));
        }
        return $this->render('transporte/utilidad/transporte/recogida/correccion/nuevo.html.twig', array(
            'arRecogida' => $arRecogida,
            'form' => $form->createView()));
    }

}

