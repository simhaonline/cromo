<?php

namespace App\Controller\Transporte\Administracion\Comercial\FacturaTipo;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\Estructura\MensajesController;
use App\Controller\MaestroController;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use App\Entity\Transporte\TteCondicionFlete;
use App\Entity\Transporte\TteDescuentoZona;
use App\Entity\Transporte\TteFacturaTipo;
use App\Form\Type\Transporte\CondicionType;
use App\Form\Type\Transporte\CondicionFleteType;
use App\Form\Type\Transporte\FacturaTipoType;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class FacturaTipoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TteFacturaTipo";
    protected $entidad = TteFacturaTipo::class;

    /**
     * @Route("/transporte/administracion/comercial/facturaTipo/lista", name="transporte_administracion_comercial_facturaTipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigo', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
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
                $em->getRepository($this->entidad)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistros = $em->getRepository($this->entidad)->lista($raw);
                $this->excelLista($arRegistros, "Facturas tipo");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/administracion/comercial/facturaTipo/lista.html.twig',[
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/facturaTipo/nuevo/{id}", name="transporte_administracion_comercial_facturaTipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new $this->entidad;
        if ($id != 0) {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        }
        $form = $this->createForm(FacturaTipoType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRegistro = $form->getData();
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_comercial_facturaTipo_detalle', array('id' => $arRegistro->getCodigoFacturaTipoPk())));
            }
        }
        return $this->render('transporte/administracion/comercial/facturaTipo/nuevo.html.twig',[
            'form' => $form->createView(),
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     * @Route("/transporte/administracion/comercial/facturaTipo/detalle/{id}", name="transporte_administracion_comercial_facturaTipo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad)->find($id);
        return $this->render('transporte/administracion/comercial/facturaTipo/detalle.html.twig',[
            'arRegistro' => $arRegistro,
        ]);
    }

    public function filtros($form)
    {
        $filtro = [
            'codigo' => $form->get('codigo')->getData(),
            'nombre' => $form->get('nombre')->getData(),
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
            $hoja->setTitle('Facturas tipo');
            $j = 0;
            $arrColumnas = [
                'ID',
                'NOMBRE',
                'CONSECUTIVO',
                'RESOLUCION FACTURA',
                'FACTURA CLASE',
                'GUIA FACTURA',
                'PREFIJO',
                ' CUENTA COBRO TIPO',
                'CUENTA INGRESO MANEJO',
                'NATURALEZA CUENTA INGRESO',
                'CUENTA CLIENTE',
                'COMPROBANTE'
            ];

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
                $hoja->setCellValue('A' . $j, $arRegistro['codigoFacturaTipoPk']);
                $hoja->setCellValue('B' . $j, $arRegistro['nombre']);
                $hoja->setCellValue('C' . $j, $arRegistro['consecutivo']);
                $hoja->setCellValue('C' . $j, $arRegistro['resolucionFacturacion']);
                $hoja->setCellValue('D' . $j, $arRegistro['codigoFacturaClaseFk']);
                $hoja->setCellValue('E' . $j, $arRegistro['guiaFactura']);
                $hoja->setCellValue('F' . $j, $arRegistro['prefijo']);
                $hoja->setCellValue('G' . $j, $arRegistro['codigoCuentaCobrarTipoFk']);
                $hoja->setCellValue('H' . $j, $arRegistro['codigoCuentaIngresoInicialFijoManejoFk']);
                $hoja->setCellValue('I' . $j, $arRegistro['codigoCuentaIngresoInicialFijoManejoFk']);
                $hoja->setCellValue('J' . $j, $arRegistro['codigoCuentaClienteFk']);
                $hoja->setCellValue('K' . $j, $arRegistro['codigoComprobanteFk']);
                $j++;
            }
            $libro->setActiveSheetIndex(0);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename={$nombre}.xls");
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($libro, 'Xls');
            $writer->save('php://output');
        } else {
            Mensajes::error("No existen registros para exportar");
        }
    }
}

