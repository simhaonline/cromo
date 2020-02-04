<?php


namespace App\Controller\Inventario\Administracion\Extranjero;


use App\Controller\MaestroController;
use App\Entity\Cartera\CarAnticipoTipo;
use App\Entity\Cartera\CarDescuentoConcepto;
use App\Entity\Cartera\CarIngresoConcepto;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Crm\CrmVisitaMotivo;
use App\Entity\Inventario\InvImportacionCostoConcepto;
use App\Entity\Inventario\InvImportacionTipo;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Form\Type\Cartera\AnticipoTipoType;
use App\Form\Type\Cartera\DescuentoConceptoType;
use App\Form\Type\Cartera\IngresoConceptoType;
use App\Form\Type\Cartera\ReciboTipoType;
use App\Form\Type\Crm\VisitaMotivoType;
use App\Form\Type\Inventario\ImportacionCostoConceptoType;
use App\Form\Type\Inventario\ImportacionTipoType;
use App\Form\Type\RecursoHumano\ExamenItemType;
use App\Form\Type\RecursoHumano\ExamenTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImportacionCostoConceptoController extends  MaestroController
{

    public $tipo = "administracion";
    public $modelo = "InvImportacionCostoConcepto";
    public $entidad = InvImportacionCostoConcepto::class;

    /**
     * @Route("/inventario/administracion/extranjero/importacioncostoconcepto/lista", name="inventario_administracion_extranjero_importacioncostoconcepto_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigo', TextType::class, array('required' => false ))
            ->add('nombre', TextType::class, array('required' => false ))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->getForm();
        $form->handleRequest($request);
        $raw=['limiteRegistros' => $form->get('limiteRegistros')->getData()];
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
                $this->excelLista($arRegistros, "importacioncostoconcepto");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/administracion/extranjero/importacioncostoconcepto/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     *  @Route("/inventario/administracion/extranjero/importacioncostoconcepto/nuevo/{id}", name="inventario_administracion_extranjero_importacioncostoconcepto_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new $this->entidad;
        if ($id != 0 ||  $id != "") {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        }
        $form = $this->createForm( ImportacionCostoConceptoType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRegistro = $form->getData();
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_extranjero_importacioncostoconcepto_detalle', array('id' => $arRegistro->getcodigoImportacionCostoConceptoPk())));
            }
        }
        return $this->render('inventario/administracion/extranjero/importacioncostoconcepto/nuevo.html.twig', [
            'form' => $form->createView(),
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     *  @Route("/inventario/administracion/extranjero/importacioncostoconcepto/detalle/{id}", name="inventario_administracion_extranjero_importacioncostoconcepto_detalle")

     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad)->find($id);
        return $this->render('inventario/administracion/extranjero/importacioncostoconcepto/detalle.html.twig', [

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
            $arrColumnas=['ID', 'NOMBRE' ];

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
                $hoja->setCellValue('A' . $j, $arRegistro['codigoImportacionCostoConceptoPk']);
                $hoja->setCellValue('B' . $j, $arRegistro['nombre']);


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