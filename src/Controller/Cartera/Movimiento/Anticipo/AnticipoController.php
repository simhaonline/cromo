<?php

namespace App\Controller\Cartera\Movimiento\Anticipo;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarAnticipo;
use App\Entity\Cartera\CarAnticipoConcepto;
use App\Entity\Cartera\CarAnticipoDetalle;
use App\Entity\Cartera\CarAnticipoTipo;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrar;
use App\Entity\Cartera\CarReciboTipo;
use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenAsesor;
use App\Form\Type\Cartera\AnticipoDetalleType;
use App\Form\Type\Cartera\AnticipoType;
use App\Formato\Cartera\Anticipo;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnticipoController extends AbstractController
{
    protected $clase = CarAnticipo::class;
    protected $claseNombre = "CarAnticipo";
    protected $modulo = "Cartera";
    protected $funcion = "Movimiento";
    protected $grupo = "Anticipo";
    protected $nombre = "Anticipo";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/cartera/movimiento/anticipo/anticipo/lista", name="cartera_movimiento_anticipo_anticipo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('numero', TextType::class, array('required' => false))
            ->add('codigoAnticipoPk', TextType::class, array('required' => false))
            ->add('codigoAnticipoTipoFk', EntityType::class, [
                'class' => CarAnticipoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('at')
                        ->orderBy('at.codigoAnticipoTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoAsesorFk', EntityType::class, [
                'class' => GenAsesor::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.codigoAsesorPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
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
                General::get()->setExportar($em->getRepository(CarAnticipo::class)->lista($raw)->getQuery()->execute(), "Anticipos");

            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarAnticipo::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_lista'));
            }
        }

        $arAnticipos = $paginator->paginate($em->getRepository(CarAnticipo::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('cartera/movimiento/anticipo/anticipo/lista.html.twig', [
            'arAnticipos' => $arAnticipos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cartera/movimiento/anticipo/anticipo/nuevo/{id}", name="cartera_movimiento_anticipo_anticipo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipo = new CarAnticipo();
        if ($id != '0') {
            $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
            if (!$arAnticipo) {
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_lista'));
            }
        } else {
            $arAnticipo->setFechaPago(new \DateTime('now'));
            $arAnticipo->setUsuario($this->getUser()->getUserName());
        }
        $form = $this->createForm(AnticipoType::class, $arAnticipo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(CarCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        if ($id == 0) {
                            $arAnticipo->setFecha(new \DateTime('now'));
                        }
                        $arAnticipo->setClienteRel($arCliente);
                        $em->persist($arAnticipo);
                        $em->flush();
                        return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', ['id' => $arAnticipo->getCodigoAnticipoPk()]));
                    }
                }

            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/nuevo.html.twig', [
            'arAnticipo' => $arAnticipo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @Route("/cartera/movimiento/anticipo/anticipo/detalle/{id}", name="cartera_movimiento_anticipo_anticipo_detalle")
     */
    public function detalle(Request $request, $id, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($id);
        $form = Estandares::botonera($arAnticipo->getEstadoAutorizado(), $arAnticipo->getEstadoAprobado(), $arAnticipo->getEstadoAnulado());
        $arrBtnActualizarDetalle = ['label' => 'Actualizar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-default']];
        $arrBtnEliminar = ['label' => 'Eliminar', 'disabled' => false, 'attr' => ['class' => 'btn btn-sm btn-danger']];
        if ($arAnticipo->getEstadoAutorizado()) {
            $arrBtnActualizarDetalle['disabled'] = true;
            $arrBtnEliminar['disabled'] = true;
        }
        $form->add('btnActualizarDetalle', SubmitType::class, $arrBtnActualizarDetalle);
        $form->add('btnEliminar', SubmitType::class, $arrBtnEliminar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arrControles = $request->request->all();
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->autorizar($arAnticipo);
                $em->getRepository(CarAnticipo::class)->liquidar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->desautorizar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnAprobar')->isClicked()) {
                $em->getRepository(CarAnticipo::class)->aprobar($arAnticipo);
                return $this->redirect($this->generateUrl('cartera_movimiento_anticipo_anticipo_detalle', array('id' => $id)));
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrDetallesSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(CarAnticipoDetalle::class)->eliminar($arAnticipo, $arrDetallesSeleccionados);
                $em->getRepository(CarAnticipo::class)->liquidar($id);
            }
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Anticipo();
                $formato->Generar($em, $id);
            }
        }

        $arAnticipoDetalles = $paginator->paginate($em->getRepository(CarAnticipoDetalle::class)->lista($id), $request->query->getInt('page', 1), 70);
        return $this->render('cartera/movimiento/anticipo/anticipo/detalle.html.twig', array(
            'arAnticipo' => $arAnticipo,
            'arAnticipoDetalles' => $arAnticipoDetalles,
            'clase' => array('clase' => 'CarAnticipo', 'codigo' => $id),
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $codigoAnticipo
     * @param $id
     * @return Response
     * @Route("/cartera/movimiento/anticipo/anticipo/detalle/nuevo/{codigoAnticipo}/{id}", name="cartera_movimiento_anticipo_anticipo_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoAnticipo, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAnticipoDetalle = new CarAnticipoDetalle();
        $arAnticipo = $em->getRepository(CarAnticipo::class)->find($codigoAnticipo);
        if ($id != 0) {
            $arAnticipoDetalle = $em->getRepository(CarAnticipoDetalle::class)->find($id);
        }
        $form = $this->createFormBuilder()
            ->add('anticipoConceptoRel', EntityType::class, [
                'class' => 'App\Entity\Cartera\CarAnticipoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'data' => $arAnticipoDetalle->getAnticipoConceptoRel()
            ])
            ->add('vrPago', IntegerType::class, array('required' => true, 'data' => $arAnticipoDetalle->getVrPago()))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $anticipoConceptoRel = $form->get('anticipoConceptoRel')->getData();
            if ($form->get('btnGuardar')->isClicked()) {
                $arAnticipoDetalle->setAnticipoConceptoRel($anticipoConceptoRel);
                $arAnticipoDetalle->setVrPago($form->get('vrPago')->getData());
                $arAnticipoDetalle->setAnticipoRel($arAnticipo);
                $em->persist($arAnticipoDetalle);
                $em->flush();
                $em->getRepository(CarAnticipo::class)->liquidar($codigoAnticipo);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('cartera/movimiento/anticipo/anticipo/detalleNuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'codigoAnticipo' => $form->get('codigoAnticipoPk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        $arAnticipoTipo = $form->get('codigoAnticipoTipoFk')->getData();
        $arsesor = $form->get('codigoAsesorFk')->getData();

        if (is_object($arAnticipoTipo)) {
            $filtro['codigoAnticipoTipo'] = $arAnticipoTipo->getCodigoAnticipoTipoPk();
        } else {
            $filtro['codigoAnticipoTipo'] = $arAnticipoTipo;
        }
        if (is_object($arsesor)) {
            $filtro['codigoAsesor'] = $arsesor->getCodigoAsesorPk();
        } else {
            $filtro['codigoAsesor'] = $arsesor;
        }

        return $filtro;

    }
}
