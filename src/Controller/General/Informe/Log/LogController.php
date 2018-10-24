<?php

namespace App\Controller\General\Informe\Log;

use App\Controller\Estructura\EntityListener;
use App\Entity\General\GenLog;
use App\Entity\General\GenLogOld;
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



}
