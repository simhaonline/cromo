<?php

namespace App\Controller\Tesoreria\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinCuenta;
use App\Entity\General\GenBanco;
use App\Entity\General\GenConfiguracion;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuCompraDetalle;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesCompra;
use App\Entity\Tesoreria\TesCompraDetalle;
use App\Entity\Tesoreria\TesCompraTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Tesoreria\CompraType;
use App\Formato\Tesoreria\Compra;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CompraController extends AbstractController
{
    protected $clase = TesCompra::class;
    protected $claseNombre = "TesCompra";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "Compra";
    protected $nombre = "Compra";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/movimiento/compra/compra/lista", name="tesoreria_movimiento_compra_compra_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCompraTipoFk', EntityType::class, [
                'class' => TesCompraTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.codigoCompraTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoCompraPk', TextType::class, array('required' => false))
            ->add('txtCodigoTercero', TextType::class, ['required' => false])
            ->add('txtNombreCorto', TextType::class, ['required' => false ,'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TesCompra::class)->lista($raw), "Compras");
            }
        }
        $arCompras = $paginator->paginate($em->getRepository(TesCompra::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('tesoreria/movimiento/compra/compra/lista.html.twig', [
            'arCompras' => $arCompras,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/tesoreria/movimiento/compra/compra/nuevo/{id}", name="tesoreria_movimiento_compra_compra_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCompra = new TesCompra();
        if ($id != 0) {
            $arCompra = $em->getRepository(TesCompra::class)->find($id);
            if (!$arCompra) {
                return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_lista'));
            }
        } else {
            $arCompra->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(CompraType::class, $arCompra);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(TesTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $arCompra->setTerceroRel($arTercero);
                        if ($id == 0) {
                            $arCompra->setFecha(new \DateTime('now'));
                        }
                        $em->persist($arCompra);
                        $em->flush();
                        return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_detalle', ['id' => $arCompra->getCodigoCompraPk()]));
                    }
                } else {
                    Mensajes::error('Debe seleccionar un tercero');
                }
            }
        }
        return $this->render('tesoreria/movimiento/compra/compra/nuevo.html.twig', [
            'arCompra' => $arCompra,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/tesoreria/movimiento/compra/compra/detalle/{id}", name="tesoreria_movimiento_compra_compra_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arCompra = $em->getRepository(TesCompra::class)->find($id);
        $form = Estandares::botonera($arCompra->getEstadoAutorizado(), $arCompra->getEstadoAprobado(), $arCompra->getEstadoAnulado());
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        $arrBtnActualizar = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnAdicionar = ['label' => 'Adicionar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCompra->getEstadoAutorizado()) {
            $arrBtnEliminar['disabled'] = true;
            $arrBtnActualizar['disabled'] = true;
            $arrBtnAdicionar['disabled'] = true;
        }
        $form
            ->add('btnEliminar', SubmitType::class, $arrBtnEliminar)
            ->add('btnActualizar', SubmitType::class, $arrBtnActualizar)
            ->add('btnAdicionar', SubmitType::class, $arrBtnAdicionar);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(TesCompraDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(TesCompra::class)->autorizar($arCompra);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                if ($arCompra->getEstadoAutorizado() == 1 && $arCompra->getEstadoAprobado() == 0) {
                    $em->getRepository(TesCompra::class)->desAutorizar($arCompra);
                    return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_detalle', ['id' => $id]));
                } else {
                    Mensajes::error("El compra debe estar autorizado y no puede estar aprobado");
                }
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(TesCompra::class)->aprobar($arCompra);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Compra();
                $formato->Generar($em, $id);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $respuesta = $em->getRepository(TesCompra::class)->anular($arCompra);
                if (count($respuesta) > 0) {
                    foreach ($respuesta as $error) {
                        Mensajes::error($error);
                    }
                }
            }
            if ($form->get('btnActualizar')->isClicked()) {
                $em->getRepository(TesCompraDetalle::class)->actualizar($arrControles, $id);
                $em->getRepository(TesCompra::class)->liquidar($id);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_detalle', ['id' => $id]));
            }
            if ($form->get('btnAdicionar')->isClicked()) {
                $em->getRepository(TesCompraDetalle::class)->actualizar($arrControles, $id);
                $arCompraDetalle = new TesCompraDetalle();
                $arCompraDetalle->setCompraRel($arCompra);
                $arCompraDetalle->setTerceroRel($arCompra->getTerceroRel());
                $arCompraDetalle->setNaturaleza('D');
                $arCompraDetalle->setNumero($arCompra->getNumeroDocumento());
                $em->persist($arCompraDetalle);
                $em->flush();
                $em->getRepository(TesCompra::class)->liquidar($id);
                return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_detalle', ['id' => $id]));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TesCompraDetalle::class)->eliminar($arCompra, $arrDetallesSeleccionados);
                $em->getRepository(TesCompra::class)->liquidar($id);
            }
            return $this->redirect($this->generateUrl('tesoreria_movimiento_compra_compra_detalle', ['id' => $id]));
        }
        $arCompraDetalles = $paginator->paginate($em->getRepository(TesCompraDetalle::class)->lista($arCompra->getCodigoCompraPk()), $request->query->getInt('page', 1), 500);
        return $this->render('tesoreria/movimiento/compra/compra/detalle.html.twig', [
            'arCompraDetalles' => $arCompraDetalles,
            'arCompra' => $arCompra,
            'clase' => array('clase' => 'TesCompra', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }


    public function getFiltros($form)
    {
        $filtro = [
            'codigoCompra' => $form->get('codigoCompraPk')->getData(),
            'codigoTercero' => $form->get('txtCodigoTercero')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCompraTipo = $form->get('codigoCompraTipoFk')->getData();

        if (is_object($arCompraTipo)) {
            $filtro['compraTipo'] = $arCompraTipo->getCodigoCompraTipoPk();
        } else {
            $filtro['compraTipo'] = $arCompraTipo;
        }

        return $filtro;

    }

}
