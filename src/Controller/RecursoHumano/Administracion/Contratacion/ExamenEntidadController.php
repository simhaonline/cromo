<?php

namespace App\Controller\RecursoHumano\Administracion\Contratacion;


use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuExamenEntidad;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Form\Type\RecursoHumano\ExamenEntidadType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ExamenEntidadController extends MaestroController
{
    public $tipo = "administracion";
    public $modelo = "RhuEntidadExamen";
    protected $entidad = RhuExamenEntidad::class;


    /**
     * @Route("/recursohumano/administracion/contratacion/examenEntidad/lista", name="recursohumano_administracion_contratacion_examenEntidad_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigo', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted() ) {
            if ($form->get('btnFiltrar')->isClicked() || $form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->filtros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $arRegistros = $em->getRepository($this->entidad)->lista($raw);
                $this->excelLista($arRegistros, "Entidades");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository($this->entidad)->eliminar($arrSeleccionados);
            }
        }
        $arRegistros = $paginator->paginate($em->getRepository($this->entidad)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/contratacion/examenEntidad/lista.html.twig', [
            'arRegistros' => $arRegistros,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/examenEntidad/nuevo/{id}", name="recursohumano_administracion_contratacion_examenEntidad_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $arRegistro = new $this->entidad();
        if ($id != '0') {
            $arRegistro = $em->getRepository($this->entidad)->find($id);
        } else {
            $arRegistro->setUsuario($this->getUser()->getUserName());
        }

        $form = $this->createForm(ExamenEntidadType::class, $arRegistro);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // guardar la tarea en la base de datos
            if ($form->get('btnGuardar')->isClicked()) {
                if ($id == 0) {
                    $arRegistro->setUsuario($this->getUser()->getUserName());
                }
                $em->persist($arRegistro);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_examenEntidad_detalle', array('id' => $arRegistro->getCodigoExamenEntidadPk())));
            }
        }
        return $this->render('recursohumano/administracion/contratacion/examenEntidad/nuevo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/contratacion/examenEntidad/detalle/{id}", name="recursohumano_administracion_contratacion_examenEntidad_detalle")
     */
    public function detalle(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $arRegistro = $em->getRepository($this->entidad )->find($id);
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']))
            ->add('btnActualizar', SubmitType::class, array('label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('btnEliminar')->isClicked()) {
                if ($arrDetallesSeleccionados) {
                    $em->getRepository(RhuExamenListaPrecio::class)->eliminar($arrDetallesSeleccionados, $id);
                }
            }
            $arrControles = $request->request->all();
            if ($form->get('btnActualizar')->isClicked()) {
//                $em->getRepository(RhuExamenListaPrecio::class)->actualizarDetalles($arrControles, $arEntidadExamenes);
                $arrPrecio = $arrControles['arrPrecio'];
                $arrCodigo = $arrControles['arrCodigo'];
                foreach ($arrCodigo as $codigoExamenListaPrecio) {
                    $arExamenListaPrecio = $em->getRepository(RhuExamenListaPrecio::class)->find($codigoExamenListaPrecio);
                    $arExamenListaPrecio->setVrPrecio($arrPrecio[$codigoExamenListaPrecio]);
//                    dd($arrPrecio[$codigoExamenListaPrecio]);
                    $em->persist($arExamenListaPrecio);

                }
                $em->flush();
            }
            return $this->redirect($this->generateUrl('recursohumano_administracion_contratacion_examenEntidad_detalle', array('id' => $id)));
        }
        $arExamenEntidadDetalles = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoExamenEntidadFk' => $id));
        return $this->render('recursohumano/administracion/contratacion/examenEntidad/detalle.html.twig', [
            'arRegistro' => $arRegistro,
            'arExamenEntidadDetalles' => $arExamenEntidadDetalles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recursohumano/administracion/examen/examenEntidad/detalle/nuevo/{codigoEntidadExamenPk}", name="recursohumano_administracion_examen_examenEntidad_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoEntidadExamenPk)
    {
        $em = $this->getDoctrine()->getManager();
        $arEntidadExamen = $em->getRepository($this->entidad)->find($codigoEntidadExamenPk);
        $arExamenTipos = $em->getRepository(RhuExamenTipo::class)->findAll();
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnGuardar')->isClicked()) {
                if (isset($arrControles['arrPrecio'])) {
                    $intIndice = 0;
                    foreach ($arrControles['arrCodigo'] as $intCodigo) {
                        if ($arrControles['arrPrecio'][$intIndice] > 0) {
                            $intDato = 0;
                            $arExamenTipos = $em->getRepository(RhuExamenTipo::class)->find($intCodigo);
                            $arEntidadExamenDetalles = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoEntidadExamenFk' => $codigoEntidadExamenPk));
                            foreach ($arEntidadExamenDetalles as $arEntidadExamenDetalle) {
                                if ($arEntidadExamenDetalle->getCodigoExamenTipoFk() == $intCodigo) {
                                    $intDato++;
                                }
                            }
                            if ($intDato == 0) {
                                $arEntidadExamenDetalle = new RhuExamenListaPrecio();
                                $arEntidadExamenDetalle->setEntidadExamenRel($arEntidadExamen);
                                $arEntidadExamenDetalle->setExamenTipoRel($arExamenTipos);
                                $vrPrecio = $arrControles['arrPrecio'][$intIndice];
                                $arEntidadExamenDetalle->setVrPrecio($vrPrecio);
                                $em->persist($arEntidadExamenDetalle);
                            }
                        }
                        $intIndice++;
                    }
                }
                $em->flush();
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('recursohumano/administracion/contratacion/examenEntidad/detalleNuevo.html.twig', [
            'arEntidadExamen' => $arEntidadExamen,
            'arExamenTipos' => $arExamenTipos,
            'form' => $form->createView()
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
            $hoja->setTitle('Entidades');
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
                $hoja->setCellValue('A' . $j, $arRegistro['codigoExamenEntidadPk']);
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
