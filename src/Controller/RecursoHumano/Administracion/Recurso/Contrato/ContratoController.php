<?php

namespace App\Controller\RecursoHumano\Administracion\Recurso\Contrato;


use App\Controller\BaseController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Form\Type\RecursoHumano\ContratoType;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContratoController extends BaseController
{
    protected $clase = RhuContrato::class;
    protected $claseFormulario = ContratoType::class;
    protected $claseNombre = "RhuContrato";
    protected $modulo = "RecursoHumano";
    protected $funcion = "administracion";
    protected $grupo = "Recurso";
    protected $nombre = "Contrato";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/administracion/recurso/contrato/lista", name="recursohumano_administracion_recurso_contrato_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('txtCodigoCliente', TextType::class, ['label' => 'Codigo cliente: ', 'required' => false, 'data' => $session->get('filtroTteCodigoCliente')])
            ->add('txtNombreCorto', TextType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNombreCliente')])
            ->add('txtNumeroIdentificacion', NumberType::class, ['label' => 'Nombre: ', 'required' => false, 'data' => $session->get('filtroTteNumeroIdentificacionCliente')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
            $session->set('filtroTteNitCliente', $form->get('txtNumeroIdentificacion')->getData());
        }
        $arContratos = $paginator->paginate($em->getRepository(RhuContrato::class)->lista(), $request->query->getInt('page', 1), 50);
        return $this->render('recursohumano/administracion/recurso/contrato/lista.html.twig',
            ['arContratos' => $arContratos,
                'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/{id}", name="recursohumano_administracion_recurso_contrato_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = new RhuContrato();
        if ($id != 0) {
            $arContrato = $em->getRepository(RhuContrato::class)->find($id);
            if (!$arContrato) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/detalle.html.twig', [
            'arContrato' => $arContrato
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/detalle/parametrosIniciales/{id}", name="recursohumano_administracion_recurso_contrato_detalle_parametrosIniciales")
     */
    public function parametrosIniciales(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arContrato = $em->getRepository(RhuContrato::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('fechaUltimoPagoCesantias', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPagoCesantias()])
            ->add('fechaUltimoPagoVacaciones', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPagoVacaciones()])
            ->add('fechaUltimoPagoPrimas', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPagoPrimas()])
            ->add('fechaUltimoPago', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',],'data' => $arContrato->getFechaUltimoPago()])
            ->add('btnGuardar',SubmitType::class,['label' => 'Guardar'])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('btnGuardar')->isClicked()){
                $arContrato->setFechaUltimoPago($form->get('fechaUltimoPago')->getData());
                $arContrato->setFechaUltimoPagoPrimas($form->get('fechaUltimoPagoPrimas')->getData());
                $arContrato->setFechaUltimoPagoVacaciones($form->get('fechaUltimoPagoVacaciones')->getData());
                $arContrato->setFechaUltimoPagoCesantias($form->get('fechaUltimoPagoCesantias')->getData());
                $em->persist($arContrato);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/administracion/recurso/contrato/parametrosIniciales.html.twig', [
            'arContrato' => $arContrato,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/administracion/recurso/contrato/nuevo/{id}", name="recursohumano_administracion_recurso_contrato_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        return $this->redirect($this->generateUrl('recursohumano_administracion_recurso_contrato_lista'));
    }
}