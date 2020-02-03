<?php

namespace App\Controller\Transporte\Administracion\Vehiculo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Formato\Transporte\Vehiculo;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Transporte\TteVehiculo;
use App\Form\Type\Transporte\VehiculoType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TteVehiculo";
    public $entidad = TteVehiculo::class;


    /**
     * @Route("/transporte/administracion/vehiculo/lista", name="transporte_administracion_transporte_vehiculo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('placa', TextType::class, array('required' => false, ))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw = ['limiteRegistros' => $form->get('limiteRegistros')->getData()];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->filtros($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository($this->entidad)-> eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistros = $em->getRepository($this->entidad)->lista($raw);
                $this->excelLista($arRegistros, "VEHICULOS");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/administracion/vehiculo/vehiculo/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/transporte/administracion/vehiculo/nuevo/{id}", name="transporte_administracion_transporte_vehiculo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $arVehiculo = new TteVehiculo();
        if ($id != '0') {
            $arVehiculo = $em->getRepository(TteVehiculo::class)->find($id);
            if (!$arVehiculo) {
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
            }
        }
        $form = $this->createForm(VehiculoType::class, $arVehiculo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                if ($id == '0') {
                    $arVehiculoValidar = $em->getRepository(TteVehiculo::class)->find($form->getData('codigoVehiculoPk'));
                    if (!$arVehiculoValidar) {
                        $em->persist($arVehiculo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
                    } else {
                        Mensajes::error("El vehiculo ya existe");
                    }
                } else {
                    $em->persist($arVehiculo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('transporte_administracion_transporte_vehiculo_lista'));
                }
            }
        }
        return $this->render('transporte/administracion/vehiculo/vehiculo//nuevo.html.twig', [
            'arVehiculo' => $arVehiculo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/transporte/administracion/vehiculo/detalle/{id}", name="transporte_administracion_transporte_vehiculo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arVehiculo = $em->getRepository(TteVehiculo::class)->find($id);
        $form = $this->createFormBuilder()
            ->add('btnImprimir', SubmitType::class, array('label' => 'Imprimir'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Vehiculo();
                $formato->Generar($em, $id);
            }
        }
        return $this->render('transporte/administracion/vehiculo/vehiculo/detalle.html.twig', [
            'clase' => array('clase' => 'TteVehiculo', 'codigo' => $id),
            'arVehiculo' => $arVehiculo,
            'form' => $form->createView()]);
    }

    public function filtros($form)
    {
        $filtro = [
            'placa' => $form->get('placa')->getData(),
        ];
        return $filtro;

    }

    public function excelLista($arRegistros, $nombre)
    {
        set_time_limit(0);
        ini_set("memory_limit", -1);
        if ($arRegistros) {
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();
            $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
            $hoja->setTitle('Items');
            $j = 0;
            $arrColumnas=['ID', 'placa', 'placa remolque', 'motor', 'numeroEjes', 'celular', 'fechaVencePoliza', 'marca', 'propietario', 'poseedor'];

            for ($i = 'A'; $j <= sizeof($arrColumnas) - 1; $i++) {
                $hoja->getColumnDimension($i)->setAutoSize(true);
                $hoja->getStyle(1)->getFont()->setName('Arial')->setSize(9);
                $hoja->getStyle(1)->getFont()->setBold(true);
                $hoja->setCellValue($i . '1', strtoupper($arrColumnas[$j]));
                $j++;
            }
            $j = 2;
            foreach ($arRegistros as $arRegistro) {
                $hoja->getStyle($j)->getFont()->setName('Arial')->setSize(9);
                $hoja->setCellValue('A' . $j, $arRegistro['codigoVehiculoPk']);
                $hoja->setCellValue('B' . $j, $arRegistro['placa']);
                $hoja->setCellValue('C' . $j, $arRegistro['modelo']);
                $hoja->setCellValue('D' . $j, $arRegistro['placaRemolque']);
                $hoja->setCellValue('E' . $j, $arRegistro['motor']);
                $hoja->setCellValue('F' . $j, $arRegistro['numeroEjes']);
                $hoja->setCellValue('G' . $j, $arRegistro['celular']);
                $hoja->setCellValue('H' . $j, $arRegistro['fechaVencePoliza']);
                $hoja->setCellValue('I' . $j, $arRegistro['marca']);
                $hoja->setCellValue('J' . $j, $arRegistro['propietario']);
                $hoja->setCellValue('K' . $j, $arRegistro['poseedor']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename={$nombre}.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        }else{
            Mensajes::error("No existen registros para exportar");
        }
    }

}

