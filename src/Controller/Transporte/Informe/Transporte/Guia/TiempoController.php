<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Controller\MaestroController;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteInformeTiempo;
use App\Entity\Transporte\TteNovedad;
use App\General\General;
use App\Utilidades\Mensajes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TiempoController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "ttei0018";


    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/tiempo", name="transporte_informe_transporte_guia_tiempo")
     */
    public function lista(Request $request)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime('now');
        if ($session->get('filtroFechaDesde') == "") {
            $session->set('filtroFechaDesde', $fecha->format('Y-m-d'));
        }
        if ($session->get('filtroFechaHasta') == "") {
            $session->set('filtroFechaHasta', $fecha->format('Y-m-d'));
        }
        $arInformeTiempos = null;
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'data' => date_create($session->get('filtroTteFechaDesde'))])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'data' => date_create($session->get('filtroTteFechaHasta'))])
            ->add('txtCodigoCliente', TextType::class, ['required' => false, 'data' => $session->get('filtroTteCodigoCliente'), 'attr' => ['class' => 'form-control']])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'data' => $session->get('filtroTteNombreCliente'), 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $raw = [];
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                    $session = new session;
                    $session->set('filtroTteFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                    $session->set('filtroTteFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    if ($form->get('txtCodigoCliente')->getData() != '') {
                        $arInformeTiempos = $em->getRepository(TteInformeTiempo::class)->informe()->getQuery()->getResult();
                    } else {
                        Mensajes::error("Debe seleccionar un cliente");
                    }

                    $filtros = [
                        'fechaDesde' => $form->get('fechaDesde')->getData()->format('Y-m-d'),
                        'fechaHasta' => $form->get('fechaHasta')->getData()->format('Y-m-d'),
                        'codigoCliente' => $form->get('txtCodigoCliente')->getData()
                    ];
                    $raw['filtros'] = $filtros;
                }
                if ($form->get('btnExcel')->isClicked()) {
                    General::get()->setExportar($em->getRepository(TteInformeTiempo::class)->excel(), "Informe tiempo entrega");
                }
            }
        }

        return $this->render('transporte/informe/transporte/guia/tiempo.html.twig', [
            'arInformeTiempos' => $arInformeTiempos,
            'form' => $form->createView()]);
    }

    public function exportarExcel1($arrGuias, $arrNovedades) {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arrGuias) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('guias');
            $j = 0;
            $arrColumnas = ['GUIA', 'FECHA', 'DOCUMENTO', 'CLIENTE', 'DESTINATARIO', 'DESTINO', 'DESPACHO', 'ENTREGA', 'SOPORTE', 'UND', 'FLETE', 'MANEJO', 'DES', 'ENT', 'NOV'];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setBold(true);;
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arrGuias as $arrGuia) {
                //$spreadsheet->getActiveSheet()->getStyle($i)->getFont()->setBold(false);
                $hoja->setCellValue('A' . $j, $arrGuia['codigoGuiaPk']);
                $hoja->setCellValue('B' . $j, $arrGuia['fechaIngreso']->format('Y-m-d'));
                $hoja->setCellValue('C' . $j, $arrGuia['documentoCliente']);
                $hoja->setCellValue('D' . $j, $arrGuia['cliente']);
                $hoja->setCellValue('E' . $j, $arrGuia['nombreDestinatario']);
                $hoja->setCellValue('F' . $j, $arrGuia['ciudadDestinoNombre']);
                $hoja->setCellValue('G' . $j, $arrGuia['fechaDespacho']?$arrGuia['fechaDespacho']->format('Y-m-d'):null);
                $hoja->setCellValue('H' . $j, $arrGuia['fechaEntrega']?$arrGuia['fechaEntrega']->format('Y-m-d'):null);
                $hoja->setCellValue('I' . $j, $arrGuia['fechaSoporte']?$arrGuia['fechaSoporte']->format('Y-m-d'):null);
                $hoja->setCellValue('J' . $j, $arrGuia['unidades']);
                $hoja->setCellValue('K' . $j, $arrGuia['vrFlete']);
                $hoja->setCellValue('L' . $j, $arrGuia['vrManejo']);
                $hoja->setCellValue('M' . $j, $arrGuia['estadoDespachado']?'SI':'NO');
                $hoja->setCellValue('N' . $j, $arrGuia['estadoEntregado']?'SI':'NO');
                $hoja->setCellValue('O' . $j, $arrGuia['estadoNovedad']?'SI':'NO');
                $j++;
            }

            $hoja2 = new Worksheet($libro, "novedades");
            $libro->addSheet($hoja2);
            $j = 0;
            $arrColumnas = ['ID', 'GUIA', 'FECHA', 'NOVEDAD', 'DESCRIPCION'];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja2->getColumnDimension($i)->setAutoSize(true);
                $hoja2->getStyle(1)->getFont()->setBold(true);;
                $hoja2->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arrNovedades as $arrNovedad) {
                $hoja2->setCellValue('A' . $j, $arrNovedad['codigoNovedadPk']);
                $hoja2->setCellValue('B' . $j, $arrNovedad['codigoGuiaFk']);
                $hoja2->setCellValue('C' . $j, $arrNovedad['fecha']->format('Y-m-d'));
                $hoja2->setCellValue('D' . $j, $arrNovedad['novedadTipoNombre']);
                $hoja2->setCellValue('E' . $j, $arrNovedad['descripcion']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=estadoGuias.xls");
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }

}

