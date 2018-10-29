<?php

namespace App\Controller\General\Informe\Log;

use App\Controller\Estructura\AdministracionController;
use App\Controller\Estructura\EntityListener;
use App\Entity\General\GenModelo;
use App\General\General;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $qbGenLog=$em->getRepository('App:General\GenLog')->lista();
        $form = $this->createFormBuilder()
            ->add('txtCodigoRegistro', TextType::class, ['required' => false, 'data' => $session->get('filtroGenLogCodigoRegistro'), 'attr' => ['class' => 'form-control']])
            ->add('dtmFechaDesde', DateType::class, ['format'=>'yyyyMMdd', 'data' => $dateFechaDesde])
            ->add('dtmFechaHasta', DateType::class, ['format'=>'yyyyMMdd', 'data' => $dateFechaHasta])
            ->add('SelAccion', ChoiceType::class, [
                'label' => 'Accion', 'data' => $session->get('TxtAccion'),
                'placeholder' => 'TODO',
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
                'placeholder'=>'TODO',
                'required'=>false,
            ])
            ->add('filtrarFecha', CheckboxType::class, ['required'=>false, 'data'=>false])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
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
        if ($form->isSubmitted() && $form->isValid()) {
        if($form->get('btnExcel')->isClicked()){
            $arGenlogExecute=$qbGenLog->getQuery()->getResult();
            foreach ($arGenlogExecute as $arGenlogEx ){
                $arGenlogEx['camposSeguimiento']=json_decode($arGenlogEx['camposSeguimiento'],true);
            }
            $this->generarExcel($arGenlogExecute,"Excel");
        }
        }
        $arGenLog= $paginator->paginate($qbGenLog,$request->query->getInt('page',1),20);
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
            'detalles' => $detalles,
        ]);
    }

    /**
     * @Route("/general/informe/log/lista/detalle/comparativo/{codigoRegistro}/{entidad}", name="general_informe_log_lista_detalle_comparativo")
     */
    public function logDetalleComparativo(Request $request, $codigoRegistro, $entidad){
        $em= $this->getDoctrine()->getManager();
        $detalles=$em->getRepository('App:General\GenLog')->getCampoSeguimiento($codigoRegistro, $entidad);
//        $arLogGenJson   = json_decode($detalles, true);
        $getCampoSeguimiento=[];
        foreach ($detalles as $json){
            array_push($getCampoSeguimiento, $json['camposSeguimiento']);
        }
        $detalleSeguimiento = $getCampoSeguimiento;


        $cabeceras=json_decode($detalleSeguimiento[0], true);
        $cabeceras=array_keys($cabeceras);
        array_unshift($cabeceras,"fecha","accion");
            for ($i=0;$i<count($detalleSeguimiento);$i++) {
                $detalleSeguimiento[$i]=json_decode($detalleSeguimiento[$i], true);
                $strFecha=$detalles[$i]['fecha']->format('Y-m-d H:i:s');
                $detalleSeguimiento[$i]=array("fecha"=>$strFecha,"accion"=>$detalles[$i]['accion'])+$detalleSeguimiento[$i];
                $actualizacionCabeceras = array_keys($detalleSeguimiento[$i]);
                $nuevo=array_diff($actualizacionCabeceras, $cabeceras);
                if(count($nuevo)>0){
                    foreach ($nuevo as $n){
                        array_push($cabeceras,$n);
                    }
                }
            }
        $form = $this->createFormBuilder()
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('btnExcel')->isClicked()){
                ob_clean();
                $this->generarExcelLogComparativo($detalleSeguimiento,"ExcelDetalleSeguimiento");
            }
        }

        return $this->render('general/log/detalleLogComparativo.html.twig', [
            'detalles'      =>  $detalleSeguimiento,
            'cabeceras'     =>  $cabeceras,
            'form' => $form->createView()
        ]);
    }

    public function generarExcel($data, $nombre){

        ob_clean();
        $excel=new AdministracionController();
        $excel->generarExcel($data,$nombre);
    }


    /**
     * @author Alexander Ceballos Luna
     * @param $arrDatos
     * @param $nombre
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generarExcelLogComparativo($arrDatos, $nombre)
    {
        if (count($arrDatos) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $j = 0;
            //Se obtienen las columnas del archivo
            $arrColumnas = array_keys($arrDatos[0]);
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $sheet->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
                $j++;
            }
            $j = 1;
            foreach ($arrDatos as $datos) {
                $i = 'A';
                $j++;
                for ($col = 0; $col <= sizeof($arrColumnas) - 1; $col++) {
                    $dato = $datos[$arrColumnas[$col]];
                    if ($dato instanceof \DateTime) {
                        $dato = $dato->format('Y-m-d');
                    }
                    $spreadsheet->getActiveSheet()->getStyle($i)->getFont()->setBold(false);

                    $sheet->setCellValue($i . $j, $dato);
                    if($arrColumnas[$col]==="fecha" || $arrColumnas[$col]==="accion"){
                        $sheet->getStyle($i . $j)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('337ab7');
                    }
                    else if($j>2 && $dato!==$arrDatos[($j-3)][$arrColumnas[$col]]){
                        $sheet->getStyle($i . $j.":".$i . $j)
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('4F805D');
                    }
                    $i++;
                }
            }
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename='{$nombre}.xls'");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        } else {
            MensajesController::error('El listado esta vacío, no hay nada que exportar');
        }
    }



}
