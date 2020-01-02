<?php

namespace App\Controller\Turno\Proceso\Venta;

use App\Entity\Transporte\TteDespacho;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use App\Entity\Turno\TurContrato;
use App\General\General;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenerarPedidoController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/turno/proceso/ventas/generarpedido", name="turno_proceso_venta_generarpedido")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        //$arContratos = null;
        $fecha = new \DateTime('now');
        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');
        $fecha = $anio . "/" . $mes . "/01";
        $form = $this->createFormBuilder()
            ->add('anio', ChoiceType::class, array(
                'choices' => array(
                    $anio - 1 => $anio - 1, $anio => $anio, $anio + 1 => $anio + 1
                ),
                'data' => $anio,
            ))
            ->add('mes', ChoiceType::class, array(
                'choices' => array(
                    'Enero' => '01', 'Febrero' => '02', 'Marzo' => '03', 'Abril' => '04', 'Mayo' => '05', 'Junio' => '06', 'Julio' => '07',
                    'Agosto' => '08', 'Septiembre' => '09', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12',
                ),
                'data' => $mes,
            ))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGenerar', SubmitType::class, ['label' => 'Generar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $anio = $form->get('anio')->getData();
            $mes = $form->get('mes')->getData();
            $fecha = $anio . "/" . $mes . "/01";
            if ($form->get('btnFiltrar')->isClicked()) {
                $arContratos = $paginator->paginate($em->getRepository(TurContrato::class)->listaGenerarPedido($fecha), $request->query->getInt('page', 1),1000);
            }
            if ($form->get('btnGenerar')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TurContrato::class)->generarPedido($arrSeleccionados, $fecha, $this->getUser()->getUserName());
            }
            if ($form->get('btnExcel')->isClicked()){
                $arPedidos = $em->getRepository(TurContrato::class)->listaGenerarPedido($fecha);
                $this->exportarExcelPersonalizado($arPedidos);
            }
        }
        $arContratos = $paginator->paginate($em->getRepository(TurContrato::class)->listaGenerarPedido($fecha), $request->query->getInt('page', 1),1000);
        return $this->render('turno/proceso/venta/generarPedido.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

    public function exportarExcelPersonalizado($arMovimientos){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arMovimientos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas=[
                'ID',
                'TIPO',
                'NIT',
                'CLIENTE',
                'SECTOR',
                'F_GENERACION',
                'H',
                'HD',
                'HN',
                'TOTAL',
                'AUT',
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arMovimientos as $arMovimiento) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arMovimiento['codigoContratoPk']);
                $hoja->setCellValue('C' . $j, $arMovimiento['contratoTipoNombre']);
                $hoja->setCellValue('B' . $j, $arMovimiento['clienteNumeroIdentificacion']);
                $hoja->setCellValue('E' . $j, $arMovimiento['clienteNombreCorto']);
                $hoja->setCellValue('D' . $j, $arMovimiento['sectorNombre']);
                $hoja->setCellValue('F' . $j, $arMovimiento['fechaGeneracion']->format('Y/m/d'));
                $hoja->setCellValue('G' . $j, $arMovimiento['horas']);
                $hoja->setCellValue('H' . $j, $arMovimiento['horasDiurnas']);
                $hoja->setCellValue('I' . $j, $arMovimiento['horasNocturnas']);
                $hoja->setCellValue('J' . $j, $arMovimiento['vrTotal']);
                $hoja->setCellValue('K' . $j, $arMovimiento['estadoAutorizado']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=Contratos.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}

