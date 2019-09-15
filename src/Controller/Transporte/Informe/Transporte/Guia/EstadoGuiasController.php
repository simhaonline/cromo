<?php

namespace App\Controller\Transporte\Informe\Transporte\Guia;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuia;
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

class EstadoGuiasController extends Controller
{
    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/informe/transporte/guia/guias/estado", name="transporte_informe_transporte_guia_guias_estado")
     */
    public function lista(Request $request, \Swift_Mailer $mailer)
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
        $arGuias = null;
        $form = $this->createFormBuilder()
            ->add('btnEnviar', SubmitType::class, array('label' => 'Enviar correo'))
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
                if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked() || $form->get('btnEnviar')->isClicked()) {
                    $session = new session;
                    $session->set('filtroTteFechaDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
                    $session->set('filtroTteFechaHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
                    $session->set('filtroTteCodigoCliente', $form->get('txtCodigoCliente')->getData());
                    $session->set('filtroTteNombreCliente', $form->get('txtNombreCorto')->getData());
                    if ($form->get('txtCodigoCliente')->getData() != '') {
                        $arGuias = $em->getRepository(TteGuia::class)->estadoGuia()->getQuery()->getResult();
                    }
                    $filtros = [
                        'fechaDesde' => $form->get('fechaDesde')->getData()->format('Y-m-d'),
                        'fechaHasta' => $form->get('fechaHasta')->getData()->format('Y-m-d'),
                        'codigoCliente' => $form->get('txtCodigoCliente')->getData()
                    ];
                    $raw['filtros'] = $filtros;
                }
                if ($form->get('btnExcel')->isClicked()) {
                    if($raw['filtros']['fechaDesde'] && $raw['filtros']['fechaHasta'] && $raw['filtros']['codigoCliente']) {
                        $arrGuias = $em->getRepository(TteGuia::class)->estadoGuia()->getQuery()->getResult();
                        $arrNovedades = $em->getRepository(TteNovedad::class)->fechaGuia($raw);
                        $this->exportarExcel($arrGuias, $arrNovedades);
                    } else {
                        Mensajes::error("Debe seleccionar las fechas y el cliente");
                    }
                }
                if ($form->get('btnEnviar')->isClicked()) {
                    $codigoCliente = $form->get('txtCodigoCliente')->getData();
                    if ($codigoCliente != "") {
                        $arCliente = $em->getRepository(TteCliente::class)->find($codigoCliente);
                        if ($arCliente) {
                            $correo = strtolower($arCliente->getCorreo());
                            if ($correo) {
                                $pos = strpos($correo, ",");
                                if ($pos === false) {
                                    $destinatario = explode(';', $correo);
                                    $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->estadoGuia($codigoCliente)->getQuery()->getResult();
                                    $cuerpo = $this->render('transporte/informe/transporte/guia/correo.html.twig', [
                                        'arGuias' => $arGuias,
                                        'form' => $form->createView()]);
                                    $message = (new \Swift_Message('Guias cliente'))
                                        ->setFrom('infologicuartas@gmail.com')
                                        ->setTo($destinatario)
                                        ->setBody(
                                            $cuerpo,
                                            'text/html'
                                        );
                                    $mailer->send($message);
                                } else {
                                    Mensajes::error("El correo del cliente " . $correo . " contiene carcteres invalidos");
                                }
                            } else {
                                Mensajes::error("El cliente no tiene correo asignado");
                            }
                        }
                        $arGuias = $this->getDoctrine()->getRepository(TteGuia::class)->estadoGuia($codigoCliente);
                    }
                }
            }
        }

        return $this->render('transporte/informe/transporte/guia/estadoGuias.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView()]);
    }

    private function exportarExcel($arrGuias, $arrNovedades) {
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

