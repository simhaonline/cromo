<?php


namespace App\Controller\Turno\Administracion\Venta;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Turno\TurItem;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\Form\Type\Turno\ItemType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends  MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TurItem";
    private $entidad = TurItem::class;

    /**
     * @Route("/turno/administracion/venta/item/lista", name="turno_administracion_venta_item_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('codigo', TextType::class, array('required' => false ))
            ->add('nombre', TextType::class, array('required' => false ))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw =['limiteRegistros' => $form->get('limiteRegistros')->getData()];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->filtros($form);
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository($this->entidad)-> eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('turno_administracion_venta_item_lista'));
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistros = $em->getRepository($this->entidad)->lista($raw);
                $this->excelLista($arRegistros, "Items");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('turno/administracion/comercial/item/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/turno/administracion/venta/item/nuevo/{id}", name="turno_administracion_venta_item_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new $this->entidad;
        if ($id != 0) {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        }
        $form = $this->createForm( ItemType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRegistro = $form->getData();
                $arRegistro->setPorcentajeIva($arRegistro->getImpuestoIvaVentaRel()->getPorcentaje());
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('turno_administracion_venta_item_detalle', array('id' => $arRegistro->getCodigoItemPk())));
            }
        }
        return $this->render('turno/administracion/comercial/item/nuevo.html.twig', [
            'form' => $form->createView(),
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     * @Route("/turno/administracion/comercial/item/detalle/{id}", name="turno_administracion_venta_item_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad)->find($id);
        return $this->render('turno/administracion/comercial/item/detalle.html.twig', [
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
            $hoja->setTitle('Items');
            $j = 0;
            $arrColumnas=['ID', 'NOMBRE', 'IVA', '%', 'IRTE'];

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
                $hoja->setCellValue('A' . $j, $arRegistro['codigoItemPk']);
                $hoja->setCellValue('B' . $j, $arRegistro['nombre']);
                $hoja->setCellValue('C' . $j, $arRegistro['codigoImpuestoIvaVentaFk']);
                $hoja->setCellValue('D' . $j, $arRegistro['porcentajeIva']);
                $hoja->setCellValue('E' . $j, $arRegistro['codigoImpuestoRetencionFk']);
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