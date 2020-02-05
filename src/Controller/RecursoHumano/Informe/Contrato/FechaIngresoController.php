<?php


namespace App\Controller\RecursoHumano\Informe\Contrato;


use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuContrato;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FechaIngresoController extends MaestroController
{

    public $tipo = "proceso";
    public $proceso = "rhui0001";

    /**
     * @Route("/recursohumano/informe/contrato/fecha/ingreso/lista", name="recursohumano_informe_contrato_fecha_ingreso_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$dateFechaDesde])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$dateFechaHasta])
            ->add('estadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false ])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()){
                $raw['filtros'] = $this->getFiltros($form);
                $arContratos = $em->getRepository(RhuContrato::class)->informeContratoFechaIngreso($raw);
                $this->exportarExcelPersonalizado($arContratos);
            }
        }
        $arContratos = $paginator->paginate($em->getRepository(RhuContrato::class)->informeContratoFechaIngreso($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/informe/nomina/contrato/FechaIngreso.html.twig', [
            'arContratos' => $arContratos,
            'form' => $form->createView(),
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [

            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoTerminado' => $form->get('estadoTerminado')->getData(),

        ];

        return $filtro;

    }

    public function exportarExcelPersonalizado($arContratos){
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arContratos) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Movimientos');
            $j = 0;
            $arrColumnas=[ 'ID','FECHA','DESDE', 'HASTA','TIPO','CARGO','EMPLEADO','NUMERO IDENTIFICACION', 'CENTRO COSTO'];

            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arContratos as $arContrato) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);

                $hoja->setCellValue('A' . $j, $arContrato['codigoContratoPk']);
                $hoja->setCellValue('B' . $j, $arContrato['fecha']->format('Y/m/d'));
                $hoja->setCellValue('C' . $j, $arContrato['fechaDesde']->format('Y/m/d'));
                $hoja->setCellValue('D' . $j, $arContrato['fechaHasta']->format('Y/m/d'));
                $hoja->setCellValue('E' . $j, $arContrato['tipo']);
                $hoja->setCellValue('F' . $j, $arContrato['cargo']);
                $hoja->setCellValue('G' . $j, $arContrato['nombreEmpleado']);
                $hoja->setCellValue('H' . $j, $arContrato['numeroIdentificacion']);
                $hoja->setCellValue('I' . $j, $arContrato['centroCosto']);

                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=FechaIngreso.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}