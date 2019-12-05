<?php

namespace App\Controller\Tesoreria\Movimiento;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\General\GenBanco;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesMovimientoDetalle;
use App\Entity\Tesoreria\TesTercero;
use App\Form\Type\Cartera\CuentaCobrarEditarType;
use App\Form\Type\Cartera\CuentaCobrarType;
use App\Form\Type\Tesoreria\CuentaPagarType;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuentaPagarController extends AbstractController
{
    protected $clase = TesCuentaPagar::class;
    protected $claseNombre = "TesCuentaPagar";
    protected $modulo = "Tesoreria";
    protected $funcion = "Movimiento";
    protected $grupo = "CuentaPagar";
    protected $nombre = "CuentaPagar";

    /**
     * @param Request $request
     * @return Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/tesoreria/movimiento/cuenta/pagar/lista", name="tesoreria_movimiento_cuentapagar_cuentapagar_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoCuentaPagarTipoFk', EntityType::class, [
                'class' => TesCuentaPagarTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cpt')
                        ->orderBy('cpt.codigoCuentaPagarTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoBancoFk', EntityType::class, [
                'class' => GenBanco::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cpb')
                        ->orderBy('cpb.codigoBancoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('codigoCuentaPagarPk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('txtCodigoTercero', TextType::class, ['required' => false])
            ->add('txtNombreCorto', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control', 'readonly' => 'reandonly']])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn btn-sm btn-danger']])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn btn-sm btn-default']])
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
                General::get()->setExportar($em->getRepository(TesCuentaPagar::class)->lista($raw), "Egresos");
            }
        }
        $arCuentasPagar = $paginator->paginate($em->getRepository(TesCuentaPagar::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('tesoreria/movimiento/cuentapagar/cuentapagar/lista.html.twig', [
            'arCuentasPagar' => $arCuentasPagar,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/compra/movimiento/cuenta/pagar/nuevo/{id}", name="tesoreria_movimiento_cuentapagar_cuentapagar_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaPagar = new TesCuentaPagar();
        if ($id != 0) {
            $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($id);
        }
        $form = $this->createForm(CuentaPagarType::class, $arCuentaPagar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoTercero = $request->request->get('txtCodigoTercero');
                if ($txtCodigoTercero != '') {
                    $arTercero = $em->getRepository(TesTercero::class)->find($txtCodigoTercero);
                    if ($arTercero) {
                        $objFunciones = new FuncionesController();
                        $arCuentaPagar->setTerceroRel($arTercero);
                        $arCuentaPagar->setModulo("tes");
                        $arCuentaPagar->setModelo("TesCuentaPagar");
                        $arCuentaPagar->setFechaVence($objFunciones->sumarDiasFechaNumero($arCuentaPagar->getPlazo(), $arCuentaPagar->getFecha()));
                        $arCuentaPagar->setOperacion($arCuentaPagar->getCuentaPagarTipoRel()->getOperacion());
                        $arCuentaPagar->setVrSaldoOperado($arCuentaPagar->getVrTotal() * $arCuentaPagar->getOperacion());
                        $arCuentaPagar->setEstadoAutorizado(1);
                        $arCuentaPagar->setEstadoAprobado(1);
                        $arCuentaPagar->setSaldoInicial(1);
                        $arCuentaPagar->setVrSaldo($arCuentaPagar->getVrTotal());
                        $em->persist($arCuentaPagar);
                        $em->flush();
                        return $this->redirect($this->generateUrl('tesoreria_movimiento_cuentapagar_cuentapagar_detalle', ['id' => $arCuentaPagar->getCodigoCuentaPagarPk()]));
                    }
                }
            }
        }
        return $this->render('tesoreria/movimiento/cuentapagar/cuentapagar/editarTercero.html.twig', [
            'arCuentaPagar' => $arCuentaPagar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/compra/movimiento/cuenta/pagar/detalle/{id}", name="tesoreria_movimiento_cuentapagar_cuentapagar_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();
        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($id);
        $form = Estandares::botonera($arCuentaPagar->getEstadoAutorizado(), $arCuentaPagar->getEstadoAprobado(), $arCuentaPagar->getEstadoAnulado());

        $arrBtnVerificar = ['label' => 'Verificar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        if ($arCuentaPagar->getEstadoVerificado()) {
            $arrBtnVerificar['disabled'] = true;
        }
        $form
            ->add('btnVerificar', SubmitType::class, $arrBtnVerificar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnVerificar')->isClicked()) {
                $em->getRepository(TesCuentaPagar::class)->verificar($arCuentaPagar);
            }
            return $this->redirect($this->generateUrl('tesoreria_movimiento_cuentapagar_cuentapagar_detalle', ['id' => $id]));
        }

        return $this->render('tesoreria/movimiento/cuentapagar/cuentapagar/detalle.html.twig', [
            'arCuentaPagar' => $arCuentaPagar,
            'clase' => array('clase' => 'TesCuentaPagar', 'codigo' => $id),
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/tesoreria/movimiento/cuentapagar/cuentapagar/referencia/{id}", name="tesoreria_movimiento_cuentapagar_cuentapagar_referencia")
     */
    public function referencia($id)
    {
        $em = $this->getDoctrine()->getManager();
        $arCuentaPagar = $em->getRepository(TesCuentaPagar::class)->find($id);
        $arMovimientoDetalles = $em->getRepository(TesMovimientoDetalle::class)->referencia($id);
        return $this->render('tesoreria/movimiento/cuentapagar/cuentapagar/referencia.html.twig', [
            'arCuentaPagar' => $arCuentaPagar,
            'arMovimientoDetalles' => $arMovimientoDetalles
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCuentaPagar' => $form->get('codigoCuentaPagarPk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoTercero' => $form->get('txtCodigoTercero')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arCuentaPagarTipo = $form->get('codigoCuentaPagarTipoFk')->getData();
        $arCuentaPagarBanco = $form->get('codigoBancoFk')->getData();

        if (is_object($arCuentaPagarTipo)) {
            $filtro['cuentaPagarTipo'] = $arCuentaPagarTipo->getCodigoCuentaPagarTipoPk();
        } else {
            $filtro['cuentaPagarTipo'] = $arCuentaPagarTipo;
        }
        if (is_object($arCuentaPagarBanco)) {
            $filtro['cuentaPagarBanco'] = $arCuentaPagarBanco->getCodigoBancoPk();
        } else {
            $filtro['cuentaPagarBanco'] = $arCuentaPagarBanco;
        }

        return $filtro;

    }
}
