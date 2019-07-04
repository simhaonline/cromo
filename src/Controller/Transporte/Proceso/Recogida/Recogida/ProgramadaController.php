<?php

namespace App\Controller\Transporte\Proceso\Recogida\Recogida;

use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteRecogidaProgramada;
use App\Form\Type\RecogidaProgramadaType;
use App\General\General;
use App\Utilidades\Mensajes;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgramadaController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/transporte/proceso/recogida/recogida/programada", name="transporte_proceso_recogida_recogida_programada")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->add('btnGenerarTodos', SubmitType::class, array('label' => 'Generar todos'))
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-default']])
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRecogidaProgramada::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('transporte_proceso_recogida_recogida_programada'));
            }
            if ($form->get('btnGenerarTodos')->isClicked()) {
                $em->getRepository(TteRecogidaProgramada::class)->generarTodos();
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteRecogidaProgramada::class)->generarSeleccionados($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(TteRecogidaProgramada::class)->lista(), "Recogidas programadas");
            }
        }
        $query = $this->getDoctrine()->getRepository(TteRecogidaProgramada::class)->lista();
        $arRecogidasProgramadas = $paginator->paginate($query, $request->query->getInt('page', 1), 100);
        return $this->render('transporte/proceso/recogida/recogida/programada.html.twig', ['arRecogidasProgramadas' => $arRecogidasProgramadas, 'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/proceso/recogida/recogida/nuevo/{codigoRecogidaProgramada}", name="transporte_proceso_recogida_recogida_nuevo")
     */
    public function nuevo(Request $request, $codigoRecogidaProgramada)
    {
        $em = $this->getDoctrine()->getManager();
        if ($codigoRecogidaProgramada == 0) {
            $arRecogidaProgramada = new TteRecogidaProgramada();
            $arRecogidaProgramada->setHora(new \DateTime('now'));
        } else {
            $arRecogidaProgramada = $em->getRepository(TteRecogidaProgramada::class)->find($codigoRecogidaProgramada);
        }

        $form = $this->createForm(\App\Form\Type\Transporte\RecogidaProgramadaType::class, $arRecogidaProgramada);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRecogidaProgramada = $form->getData();
            $txtCodigoCliente = $request->request->get('txtCodigoCliente');
            if ($txtCodigoCliente != '') {
                $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                if ($arCliente) {
                    $arRecogidaProgramada->setClienteRel($arCliente);
                    $arRecogidaProgramada->setOperacionRel($this->getUser()->getOperacionRel());
                    $em->persist($arRecogidaProgramada);
                    $em->flush();
                    if ($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_proceso_recogida_recogida_nuevo', array('codigoRecogida' => 0)));
                    }

                    if ($form->get('guardar')->isClicked()) {
                        return $this->redirect($this->generateUrl('transporte_proceso_recogida_recogida_programada'));

                    }
                }
            }
        }
        return $this->render('transporte/proceso/recogida/recogida/nuevo.html.twig', ['arRecogidaProgramada' => $arRecogidaProgramada, 'form' => $form->createView()]);
    }
}

