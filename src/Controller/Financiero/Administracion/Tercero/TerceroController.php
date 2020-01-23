<?php


namespace App\Controller\Financiero\Administracion\Tercero;

use App\Entity\Financiero\FinTercero;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Financiero\TerceroType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TerceroController extends AbstractController
{
    /**
     * @Route("/financiero/administracion/tercero/lista", name="financiero_administracion_tercero_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoTerceroPk', TextType::class, array('required' => false))
            ->add('numeroIdentificacion', NumberType::class, array('required' => false))
            ->add('nombreCorto', TextType::class, array('required' => false))
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arContratos = $em->getRepository(FinTercero::class)->listaIntercambio()->getQuery()->getResult();
                $this->exportarExcelPersonalizado($arContratos);
            }
            if ($form->get('btnEliminar')->isClicked()) {
//                $arrSeleccionados = $request->query->get('ChkSeleccionar');
//                $this->get("UtilidadesModelo")->eliminar(FinTercero::class, $arrSeleccionados);
//                return $this->redirect($this->generateUrl('financiero_administracion_tercero_lista'));
            }
        }
            $arTerceros = $paginator->paginate($em->getRepository(FinTercero::class)->listaPrincipal($raw), $request->query->getInt('page', 1), 30);
            return $this->render('financiero/administracion/tercero/lista.html.twig', [
                'arTerceros' => $arTerceros,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/financiero/administracion/tercero/nuevo/{id}", name="financiero_administracion_tercero_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arTercero = new FinTercero();
        if ($id != 0) {
            $arTercero = $em->getRepository(FinTercero::class)->find($id);
        }
        $form = $this->createForm(TerceroType::class, $arTercero);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('guardar')->isClicked()) {
                $arTercero = $form->getData();
                $em->persist($arTercero);
                $em->flush();
                return $this->redirect($this->generateUrl('financiero_administracion_tercero_detalle', array('id' => $arTercero->getCodigoTerceroPk())));
            }
        }
        return $this->render('financiero/administracion/tercero/nuevo.html.twig', [
            'arTercero' => $arTercero,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/financiero/administracion/tercero/tercero/detalle/{id}", name="financiero_administracion_tercero_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arFase = $em->getRepository(FinTercero::class)->find($id);
            if (!$arFase) {
                return $this->redirect($this->generateUrl('financiero_administracion_tercero_tercero_lista'));
            }
        }
        $arTercero = $em->getRepository(FinTercero::class)->find($id);
        return $this->render('financiero/administracion/tercero/detalle.html.twig', [
            'arTercero' => $arTercero
        ]);
    }

    public function getFiltros($form)
    {
        return $filtro = [
            'codigoTerceroPk' => $form->get('codigoTerceroPk')->getData(),
            'numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(),
            'nombreCorto' => $form->get('nombreCorto')->getData(),
        ];
    }

    public function exportarExcelPersonalizado($arTerceros)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arTerceros) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Terceros');
            $j = 0;
            $arrColumnas = [
                'NIT', 'TIPO', 'NOMBRE', 'DIRECCION', 'CIUDAD', 'TELEFONO', 'MUNICIPIO', 'ACTIVO', 'TIENE RUT', 'PAIS', 'EMAIL', 'CELULAR', 'PLAZO', 'ACTIVIDAD ECONOMICA',
                'INDICATIVO', 'NATURALEZA'
            ];
            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arTerceros as $arTercero) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                if($arTercero['codigoIdentificacionFk'] == 'NI'){
                    $tipoDocumento = 'A';
                }elseif ($arTercero['codigoIdentificacionFk'] == 'CC') {
                    $tipoDocumento = 'C';
                }
                if($arTercero['estadoInactivo'] == 0){
                    $estadoActivo = 'S';
                }else
                    $estadoActivo = 'N';
                $hoja->setCellValue('A' . $j, utf8_decode($arTercero['numeroIdentificacion']));
                $hoja->setCellValue('B' . $j, $tipoDocumento);
                $hoja->setCellValue('C' . $j, utf8_decode($arTercero['nombreCorto']));
                $hoja->setCellValue('D' . $j, utf8_decode($arTercero['direccion']));
                $hoja->setCellValue('E' . $j, utf8_decode($arTercero['ciudadNombre']));
                $hoja->setCellValue('F' . $j, utf8_decode($arTercero['telefono']));
                $hoja->setCellValue('G' . $j, utf8_decode($arTercero['codigoDaneCompleto']));
                $hoja->setCellValue('H' . $j, utf8_decode($estadoActivo));
                $hoja->setCellValue('I' . $j, 'S');
                $hoja->setCellValue('J' . $j, '169');
                $hoja->setCellValue('K' . $j, utf8_decode($arTercero['nombre1']));
                $hoja->setCellValue('L' . $j, utf8_decode($arTercero['nombre2']));
                $hoja->setCellValue('M' . $j, utf8_decode($arTercero['apellido1']));
                $hoja->setCellValue('N' . $j, utf8_decode($arTercero['apellido2']));
                $hoja->setCellValue('O' . $j, utf8_decode($arTercero['email']));
                $hoja->setCellValue('P' . $j, utf8_decode($arTercero['celular']));
                $hoja->setCellValue('Q' . $j, '');
                $hoja->setCellValue('R' . $j, '');
                $hoja->setCellValue('S' . $j, '');
                $hoja->setCellValue('T' . $j, '');
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename=terceros.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }
    }
}