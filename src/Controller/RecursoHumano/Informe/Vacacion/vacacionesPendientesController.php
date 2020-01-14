<?php


namespace App\Controller\RecursoHumano\Informe\Vacacion;


use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuInformeVacacionPendiente;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class vacacionesPendientesController extends AbstractController
{
    /**
     * @Route("/recursohumano/informe/vacacion/pendiente/lista", name="recursohumano_informe_vacacion_pendiente_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros' => $session->get('filtroInformeRhuContrato')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \DateTime('now')])
            ->add('estadoTerminado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data' => $raw['filtros']['estadoTerminado']])
            ->add('btnGenerar', SubmitType::class, array('label' => 'Generar'))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnGenerar')->isClicked()) {
                $fecha = $form->get('fechaHasta')->getData();
                $em->getRepository(RhuVacacion::class)->pendientePagarInforme($fecha);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $arVacacionPendiente = $this->getDoctrine()->getRepository(RhuInformeVacacionPendiente::class)->informe();
                $this->exportarExcelPersonalizado($arVacacionPendiente);
            }
        }

        $arVacacionesPendientes = $paginator->paginate($em->getRepository(RhuInformeVacacionPendiente::class)->informe(), $request->query->getInt('page', 1), 500);
        return $this->render('recursohumano/informe/vacacion/pendiente.html.twig', [
            'arVacacionesPendientes' => $arVacacionesPendientes,
            'form' => $form->createView(),
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'estadoTerminado' => $form->get('estadoTerminado')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
        ];
        $session->set('filtroInformeRhuContrato', $filtro);

        return $filtro;

    }

    public function exportarExcelPersonalizado($arVacacionesPendiente)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arVacacionesPendiente) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->setTitle('vacacion Pendiente');
            $j = 0;
            $arrColumnas = ['ID','CONTRATO','TIPO','INGRESO','IDENTIFICACIÓN','EMPLEADO','GRUPO', 'ZONA', 'ULT_PAGO',
                            'ULT_VAC','TER','SALARIO','R_N','PROMEDIO','DIAS','AUS','VALOR',
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arVacacionesPendiente as $arVacacionPendiente) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arVacacionPendiente['codigoInformeVacacionPendientePk']);
                $hoja->setCellValue('B' . $j, $arVacacionPendiente['codigoContratoFk']);
                $hoja->setCellValue('C' . $j, $arVacacionPendiente['tipoContrato']);
                $hoja->setCellValue('D' . $j, $arVacacionPendiente['fechaIngreso']->format('Y/m/d'));
                $hoja->setCellValue('E' . $j, $arVacacionPendiente['numeroIdentificacion']);
                $hoja->setCellValue('F' . $j, $arVacacionPendiente['empleado']);
                $hoja->setCellValue('G' . $j, $arVacacionPendiente['grupo']);
                $hoja->setCellValue('H' . $j, $arVacacionPendiente['zona']);
                $hoja->setCellValue('I' . $j, $arVacacionPendiente['fechaUltimoPago']->format('Y/m/d'));
                $hoja->setCellValue('J' . $j, $arVacacionPendiente['fechaUltimoPagoVacaciones']->format('Y/m/d'));
                $hoja->setCellValue('K' . $j, $arVacacionPendiente['estadoTerminado']);
                $hoja->setCellValue('L' . $j, $arVacacionPendiente['vrSalario']);
                $hoja->setCellValue('M' . $j, $arVacacionPendiente['vrPromedioRecargoNocturno']);
                $hoja->setCellValue('N' . $j, $arVacacionPendiente['vrSalarioPromedio']);
                $hoja->setCellValue('O' . $j, $arVacacionPendiente['dias']);
                $hoja->setCellValue('P' . $j, $arVacacionPendiente['diasAusentismo']);
                $hoja->setCellValue('Q' . $j, $arVacacionPendiente['vrVacacion']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=vacacionesPendientes.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}