<?php


namespace App\Controller\Transporte\Administracion\Trasporte\Guia;


use App\Controller\MaestroController;
use App\Entity\Transporte\TteRedespacho;
use App\Entity\Transporte\TteRedespachoMotivo;
use App\Form\Type\Transporte\RedespachoMotivoType;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class RedespachoMotivoController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "TteDestinatario";
    protected $entidad = TteRedespachoMotivo::class;

    /**
     * @Route("/transporte/administracion/transporte/guia/redespachoMotivo/lista", name="transporte_administracion_transporte_guia_redespachoMotivo_lista")
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
        $raw = ['limiteRegistros' =>  $form->get('limiteRegistros')->getData()];
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
                $this->excelLista($arRegistros, "Redespachos");
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/administracion/transporte/guia/redespachoMotivo/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     *@Route("/transporte/administracion/transporte/guia/redespachoMotivo/nuevo/{id}", name="transporte_administracion_transporte_guia_redespachoMotivo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = new $this->entidad;
        if ($id != null) {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        }
        $form = $this->createForm( RedespachoMotivoType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arRegistro = $form->getData();
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('transporte_administracion_transporte_guia_redespachoMotivo_detalle', array('id' => $arRegistro->getCodigoRedespachoMotivoPk())));
            }
        }
        return $this->render('transporte/administracion/transporte/guia/redespachoMotivo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arRegistro' => $arRegistro
        ]);
    }

    /**
     *@Route("/transporte/administracion/transporte/guia/redespachoMotivo/detalle/{id}", name="transporte_administracion_transporte_guia_redespachoMotivo_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad)->find($id);
        return $this->render('transporte/administracion/transporte/guia/redespachoMotivo/detalle.html.twig', [
            'arRegistro' => $arRegistro,
        ]);
    }

    public function filtros($form)
    {
        $session = new Session();
        $filtro = [
            'codigo' => $form->get('codigo')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        $session->set('filtroTurItem.', $filtro);
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
            $hoja->setTitle('redespacho motivos');
            $j = 0;
            $arrColumnas=['ID', 'NOMBRE'];

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
                $hoja->setCellValue('A' . $j, $arRegistro['codigoRedespachoMotivoPk']);
                $hoja->setCellValue('B' . $j, $arRegistro['nombre']);;
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