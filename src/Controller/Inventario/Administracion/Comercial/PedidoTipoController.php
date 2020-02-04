<?php

namespace App\Controller\Inventario\Administracion\Comercial;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvGrupo;
use App\Entity\Inventario\InvOrdenTipo;
use App\Entity\Inventario\InvPedidoTipo;
use App\Entity\Inventario\InvSolicitudTipo;
use App\Form\Type\Inventario\GrupoType;
use App\Form\Type\Inventario\OrdenTipoType;
use App\Form\Type\Inventario\SolicitudTipoType;
use App\Form\Type\Turno\PedidoTipoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PedidoTipoController extends MaestroController
{

    public $tipo = "administracion";
    public $modelo = "InvPedidoTipo";
    public $entidad = InvPedidoTipo::class;

    /**
     * @Route("/inventario/administracion/comercial/pedidotipo/lista", name="inventario_administracion_comercial_pedidotipo_lista")
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
                $this->excelLista($arRegistros, "PedidoTipo");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('inventario/administracion/comercial/pedidotipo/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     *  @Route("/inventario/administracion/comercial/pedidotipo/nuevo/{id}", name="inventario_administracion_comercial_pedidotipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new $this->entidad;
        if ($id != 0 ||  $id != "") {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        }
        $form = $this->createForm( \App\Form\Type\Inventario\PedidoTipoType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRegistro = $form->getData();
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('inventario_administracion_comercial_pedidotipo_detalle', array('id' => $arRegistro->getcodigoPedidoTipoPk())));
            }
        }
        return $this->render('inventario/administracion/comercial/pedidotipo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     *  @Route("/inventario/administracion/comercial/pedidotipo/detalle/{id}", name="inventario_administracion_comercial_pedidotipo_detalle")

     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad)->find($id);
        return $this->render('inventario/administracion/comercial/pedidotipo/detalle.html.twig', [

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
            $hoja->setTitle('PedidoTipo');
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
                $hoja->setCellValue('A' . $j, $arRegistro['codigoPedidoTipoPk']);
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