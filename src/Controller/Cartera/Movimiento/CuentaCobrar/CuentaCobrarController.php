<?php

namespace App\Controller\Cartera\Movimiento\CuentaCobrar;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Cartera\CarIngresoDetalle;
use App\Entity\Cartera\CarMovimientoDetalle;
use App\Entity\Cartera\CarReciboDetalle;
use App\Form\Type\Cartera\CuentaCobrarEditarType;
use App\Form\Type\Cartera\CuentaCobrarType;
use App\General\General;
use App\Utilidades\Estandares;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CuentaCobrarController extends MaestroController
{

    public $tipo = "Movimiento";
    public $modelo = "CarCuentaCobrar";


    protected $clase = CarCuentaCobrar::class;
    protected $claseNombre = "CarCuentaCobrar";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "CuentaCobrar";
    protected $nombre = "CuentaCobrar";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/cartera/cuentacobrar/lista", name="cartera_movimiento_cuentacobrar_cuentacobrar_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('numeroDocumento', TextType::class, array('required' => false))
            ->add('numeroReferencia', TextType::class, array('required' => false))
            ->add('codigoCuentaCobrarPk', TextType::class, array('required' => false))
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('codigoCuentaCobrarTipoFk', EntityType::class, [
                'class' => CarCuentaCobrarTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cct')
                        ->orderBy('cct.codigoCuentaCobrarTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
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
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(CarCuentaCobrar::class)->lista($raw)->getQuery()->execute(), "CuentasCobrar");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(CarCuentaCobrar::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_cuentacobrar_cuentacobrar_lista'));
            }
        }
        $arCuentaCobrar = $paginator->paginate($em->getRepository(CarCuentaCobrar::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('cartera/movimiento/cuentacobrar/cuentacobrar/lista.html.twig', [
            'arCuentaCobrar' => $arCuentaCobrar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/cartera/cuentacobrar/nuevo/{id}", name="cartera_movimiento_cuentacobrar_cuentacobrar_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new FuncionesController();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($id);
        if ($id != 0) {
            $form = $this->createForm(CuentaCobrarEditarType::class, $arCuentaCobrar);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $em->persist($arCuentaCobrar);
                    $em->flush();
                    return $this->redirect($this->generateUrl('cartera_movimiento_cuentacobrar_cuentacobrar_detalle', ['id' => $arCuentaCobrar->getCodigoCuentaCobrarPk()]));
                }
            }
            return $this->render('cartera/movimiento/cuentacobrar/cuentacobrar/editarAsesor.html.twig', [
                'arCuentaCobrar' => $arCuentaCobrar,
                'form' => $form->createView()
            ]);
        } else {
            $arCuentaCobrar = new CarCuentaCobrar();
            $arCuentaCobrar->setFecha(new \DateTime('now'));
            $form = $this->createForm(CuentaCobrarType::class, $arCuentaCobrar);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('guardar')->isClicked()) {
                    $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                    if ($txtCodigoCliente != '') {
                        $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                        if ($arCliente) {
                            $arCuentaCobrar->setClienteRel($arCliente);
                            $arCuentaCobrar->setModulo("CAR");
                            $arCuentaCobrar->setFechaVence($objFunciones->sumarDiasFechaNumero($arCuentaCobrar->getPlazo(), $arCuentaCobrar->getFecha()));
                            $arCuentaCobrar->setOperacion($arCuentaCobrar->getCuentaCobrarTipoRel()->getOperacion());
                            $arCuentaCobrar->setVrSaldo($arCuentaCobrar->getVrTotal());
                            $arCuentaCobrar->setVrSaldoOperado($arCuentaCobrar->getVrTotal() * $arCuentaCobrar->getOperacion());
                            $arCuentaCobrar->setEstadoAutorizado(1);
                            $arCuentaCobrar->setEstadoAprobado(1);
                            $em->persist($arCuentaCobrar);
                            $em->flush();
                            return $this->redirect($this->generateUrl('cartera_movimiento_cuentacobrar_cuentacobrar_detalle', ['id' => $arCuentaCobrar->getCodigoCuentaCobrarPk()]));
                        }
                    }

                }
            }
            return $this->render('cartera/movimiento/cuentacobrar/cuentacobrar/nuevo.html.twig', [
                'arCuentaCobrar' => $arCuentaCobrar,
                'form' => $form->createView()
            ]);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/cartera/cuentacobrar/detalle/{id}", name="cartera_movimiento_cuentacobrar_cuentacobrar_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($id);
        $form = Estandares::botonera($arCuentaCobrar->getEstadoAutorizado(), $arCuentaCobrar->getEstadoAprobado(), $arCuentaCobrar->getEstadoAnulado());
        $form->handleRequest($request);
        if ($form->get('btnAnular')->isClicked()) {
            $em->getRepository(CarCuentaCobrar::class)->anular($arCuentaCobrar);
            return $this->redirect($this->generateUrl('cartera_movimiento_cuentacobrar_cuentacobrar_detalle', ['id' => $id]));
        }
        return $this->render('cartera/movimiento/cuentacobrar/cuentacobrar/detalle.html.twig', [
            'arCuentaCobrar' => $arCuentaCobrar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cartera/movimiento/cartera/cuentacobrar/referencia/{id}", name="cartera_movimiento_cuentacobrar_cuentacobrar_referencia")
     */
    public function referencia($id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($id);
        $arReciboDetalles = $em->getRepository(CarReciboDetalle::class)->detalleReferencia($id);
        $arAplicaciones = $em->getRepository(CarAplicacion::class)->referencia($id);
        $arMovimientoDetalles = $em->getRepository(CarMovimientoDetalle::class)->referencia($id);
        return $this->render('cartera/movimiento/cuentacobrar/cuentacobrar/referencia.html.twig', [
            'arCuentaCobrar' => $arCuentaCobrar,
            'arReciboDetalles' => $arReciboDetalles,
            'arAplicaciones' => $arAplicaciones,
            'arMovimientoDetalles' => $arMovimientoDetalles
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'numeroDocumento' => $form->get('numeroDocumento')->getData(),
            'numeroReferencia' => $form->get('numeroReferencia')->getData(),
            'codigoCuentaCobrar' => $form->get('codigoCuentaCobrarPk')->getData(),
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCuentaCobrarTipo = $form->get('codigoCuentaCobrarTipoFk')->getData();

        if (is_object($arCuentaCobrarTipo)) {
            $filtro['codigoCuentaCobrarTipo'] = $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk();
        } else {
            $filtro['codigoCuentaCobrarTipo'] = $arCuentaCobrarTipo;
        }

        return $filtro;

    }
}