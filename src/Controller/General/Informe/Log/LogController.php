<?php

namespace App\Controller\General\Informe\Log;

use App\Controller\Estructura\EntityListener;
use App\Entity\General\GenLog;
use App\Entity\General\GenLogOld;
use App\Entity\General\GenModelo;
use App\Repository\General\GenLogOldRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LogController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/general/informe/log/lista", name="general_informe_log_lista")
     */
    public function lista(Request $request){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('txtCodigoRegistro', TextType::class, ['required' => false, 'data' => $session->get('filtroGenLogCodigoRegistro'), 'attr' => ['class' => 'form-control']])
            ->add('dtmFechaDesde', DateType::class, ['format'=>'yyyyMMdd', 'data' => $dateFechaDesde])
            ->add('dtmFechaHasta', DateType::class, ['format'=>'yyyyMMdd', 'data' => $dateFechaHasta])
            ->add('SelAccion', ChoiceType::class, [
                'label' => 'Accion', 'data' => $session->get('TxtAccion'),
                'placeholder' => 'Seleccione una accion',
                'choices' => [
                    EntityListener::ACCION_NUEVO => "CREACION",
                    EntityListener::ACCION_ACTUALIZAR => "ACTUALIZACION",
                    EntityListener::ACCION_ELIMINAR => "ELIMINACION",
                ],
                'required'=>false,
            ])
            ->add('SelModelo', EntityType::class, [
                'class'=>GenModelo::class,
                'choice_label'=>'codigoModeloPk',
                'placeholder'=>'Seleccione una entidad',
                'required'=>false,
            ])
            ->add('filtrarFecha', CheckboxType::class, ['required'=>false, 'data'=>false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->get('btnFiltrar')->isClicked()) {
            if ($form->get('txtCodigoRegistro')->getData() != '') {
                $session->set('filtroGenLogCodigoRegistro', $form->get('txtCodigoRegistro')->getData());
            }
            else {
                $session->set('filtroGenLogCodigoRegistro', null);
            }

            if ($form->get('filtrarFecha')->getData()) {
                $session->set('filtroGenLogFechaDesde',$form->get('dtmFechaDesde')->getData()->format('Y-m-d'));
                $session->set('filtroGenLogFechaHasta',date_modify($form->get('dtmFechaHasta')->getData(),'+1days')->format('Y-m-d'));
            }
            else{
                $session->set('filtroGenLogFechaDesde',null);
                $session->set('filtroGenLogFechaHasta',null);
            }

            if($form->get('SelAccion')->getData()!==""){
                $session->set('filtroGenLogAccion',$form->get('SelAccion')->getData());
            }
            else{
                $session->set('filtroGenLogAccion',null);
            }

            if($form->get('SelModelo')->getData()!==null){
                $session->set('filtroGenLogModelo',$form->get('SelModelo')->getData()->getCodigoModeloPk());
            }
            else{
                $session->set('filtroGenLogModelo',null);
            }

        }
        $arGenLog= $paginator->paginate($em->getRepository('App:General\GenLog')->lista(),$request->query->getInt('page',1),20);
        return $this->render('general/log/lista.html.twig',
            ['arGenLog' => $arGenLog,
                'form' => $form->createView()]);
    }

    /**
     * @Route("/general/informe/log/lista/detalle/{codigoRegistro}", name="general_informe_log_lista_detalle")
     */
    public function logDetalle($codigoRegistro){
        $em = $this->getDoctrine()->getManager();
        $detalles=$em->getRepository('App:General\GenLog')->find($codigoRegistro)->getCamposSeguimiento();
        $detalles = json_decode($detalles, true);
        if (!is_array($detalles)) {
            $detalles = [];
            $detalles['SIN REGISTRAR'] = 'N/A';
        }
        return $this->render('general/log/detalleLog.html.twig', [
            'detalles' => $detalles
        ]);
    }

    /**
     * @Route("/general/informe/log/lista/detalle/comparativo/{codigoRegistro}/{entidad}", name="general_informe_log_lista_detalle_comparativo")
     */
    public function logDetalleComparativo($codigoRegistro, $entidad){
        $em= $this->getDoctrine()->getManager();
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $detalles=$em->getRepository('App:General\GenLog')->findBy(['codigoRegistroPk'=>$codigoRegistro,'nombreEntidad'=>$entidad]);
        $arLogGenJson = $serializer->serialize($detalles, 'json');
        $arLogGenJson   = json_decode($arLogGenJson, true);
        $getCampoSeguimiento=[];
        foreach ($arLogGenJson as $key => $json){
            array_push($getCampoSeguimiento, $json['camposSeguimiento']);
        }
        $detalles = $getCampoSeguimiento;


        $cabeceras=json_decode($detalles[0], true);
        $cabeceras=array_keys($cabeceras);

        if(count($detalles)>0) {
            foreach ($detalles as $detalle) {
                $detalle=json_decode($detalle, true);
                $actualizacionCabeceras = array_keys($detalle);
                foreach ($cabeceras as $cabecera) {
                    foreach ($actualizacionCabeceras as $actualizacionCabecera){
                        if($actualizacionCabecera!==$cabecera){
                            array_push($cabeceras,$actualizacionCabecera);
                        }
                    }
                }
            }
        }
        if (!is_array($detalles)) {
            $detalles = [];
            $detalles['SIN REGISTRAR'] = 'N/A';
        }
        dump($cabeceras);
        exit();
        return $this->render('general/log/detalleLogComparativo.html.twig', [
            'detalles'      =>  $detalles,
        ]);
    }



}
