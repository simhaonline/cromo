<?php

namespace App\Controller\General\Informe\Log;

use App\Controller\Estructura\EntityListener;
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
//QVMBXCvFHaltPm/ebXycQ7rOvgbgLFfQQvSxZVa/I/FvtsO9Ps9k9B84YP6rC+1aEio86UKY0ZxwsAEV/se7+Q==





//    public function listaAction(Request $request) {
//        set_time_limit(0);
//        ini_set("memory_limit", -1);
//        $session = new Session();
//        $em = $this->getDoctrine()->getManager();
//        $paginator = $this->get('knp_paginator');
//        $form = $this->createFormBuilder()
////            ->add('txtCodigoDespacho', TextType::class, ['required' => false, 'data' => $session->get('filtroCodigoDespacho'), 'attr' => ['class' => 'form-control']])
////            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
////            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
//            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
////            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
//            ->getForm();
//        $form->handleRequest($request);
//        if ($form->get('btnFiltrar')->isClicked()) {
////            $session->set('filtroCodigoDespacho', $form->get('txtCodigoDespacho')->getData());
////            $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
////            $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
//        }
//        $query = $em->createQuery($em->getRepository(GenLogOld::class)->lista());
//        $arLogs = $paginator->paginate($query, $request->query->get('page', 1), 50);
//        return $this->render('general/log/lista.html.twig', [
//            'arLogs' => $arLogs,
//            'form' => $form->createView()]);
//    }

//    /**
//     * @Route("/gen/consulta/log/lista", name="brs_gen_consulta_log_lista")
//     * @param Request $request
//     */
//    public function listaConsultaAction(Request $request) {
//        $em = $this->getDoctrine()->getManager();
//        $paginator = $this->get('knp_paginator');
//        $form = $this->formularioLista();
//        $form->handleRequest($request);
//        $this->lista();
//        if ($form->isSubmitted() && $form->isValid()) {
//            if ($form->get('BtnFiltrar')->isClicked()) {
//                $this->filtrarLista($form);
//                $this->lista();
//            }
//            if ($form->get('BtnExcel')->isClicked()) {
//                $this->filtrarLista($form);
//                $this->generarExcel();
//            }
//        }
//
//        $arLogs = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
//        return $this->render('BrasaGeneralBundle:Consulta/Log:listaConsulta.html.twig', array(
//                    'arLogs' => $arLogs,
//                    'form' => $form->createView()
//        ));
//    }
//
//    private function lista() {
//        $session = new Session;
//        $em = $this->getDoctrine()->getManager();
//        $this->strDqlLista = $em->getRepository('BrasaGeneralBundle:GenLogOld')->listaDql(
//                $session->get('filtroCodigoUsuario'),
//                $session->get('filtroCodigoDocumento'),
//                "");
//    }
//
//    private function filtrarLista($form) {
//        $session = new Session;
//        $codigoDocumento = "";
//        $session->set('filtroCodigoUsuario', $form->get('TxtCodigoUsuario')->getData());
//        if ($form->get('documentoRel')->getData()) {
//            $codigoDocumento = $form->get('documentoRel')->getData()->getCodigoDocumentoPk();
//        }
//        $session->set('filtroCodigoDocumento', $codigoDocumento);
//    }
//
//    private function formularioLista() {
//        $session = new Session;
//        $arrayPropiedades = array(
//            'class' => 'BrasaSeguridadBundle:SegDocumento',
//            'query_builder' => function (EntityRepository $er) {
//                return $er->createQueryBuilder('d')
//                                ->orderBy('d.nombre', 'ASC');
//            },
//            'choice_label' => 'nombre',
//            'required' => false,
//            'empty_data' => "",
//            'placeholder' => "TODOS",
//            'data' => ""
//        );
//        $form = $this->createFormBuilder()
//                ->add('documentoRel', EntityType::class, $arrayPropiedades)
//                ->add('TxtCodigoUsuario', TextType::class, array('label' => 'Usuario', 'data' => $session->get('filtroCodigoUsuario')))
//                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
//                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
//                ->getForm();
//        return $form;
//    }
//
//    private function generarExcel() {
//
//    }

}
