<?php

namespace App\Controller\Transporte\Movimiento\Transporte;

use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Entity\Centro;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDesembarco;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedad;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteRecibo;
use App\Entity\Transporte\TteRedespacho;
use App\Entity\Transporte\TteServicio;
use App\Form\Type\Transporte\GuiaType;
use App\Form\Type\Transporte\NovedadType;
use App\Formato\Transporte\Guia;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GuiaController extends ControllerListenerGeneral
{
    protected $clase = TteGuia::class;
    protected $claseNombre = "TteGuia";
    protected $modulo = "Transporte";
    protected $funcion = "Movimiento";
    protected $grupo = "Transporte";
    protected $nombre = "Guia";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/transporte/movimiento/transporte/guia/lista", name="transporte_movimiento_transporte_guia_lista")
     */
    public function lista(Request $request)
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('codigoGuiaTipoFk', EntityType::class, [
                'class' => TteGuiaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gt')
                        ->orderBy('gt.codigoGuiaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoOperacionCargoFk', EntityType::class, [
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoOperacionPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoOperacionCargoIngreso', EntityType::class, [
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoOperacionPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoCiudadDestinoFk', EntityType::class, [
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoCiudadPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoClienteFk', TextType::class, array('required' => false))
            ->add('codigoServicioFk', EntityType::class, [
                'class' => TteServicio::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoServicioPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoGuiaPk', IntegerType::class, array('required' => false))
            ->add('codigoDespachoFk', IntegerType::class, array('required' => false))
            ->add('codigoFacturaFk', IntegerType::class, array('required' => false))
            ->add('numeroFactura', IntegerType::class, array('required' => false))
            ->add('numero', IntegerType::class, array('required' => false))
            ->add('remitente', TextType::class, array('required' => false))
            ->add('documentoCliente', TextType::class, array('required' => false))
            ->add('nombreDestinatario', TextType::class, array('required' => false))
            ->add('fechaIngresoDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaIngresoHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('estadoDespachado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoFacturado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoNovedad', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoNovedadSolucion', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->add('btnFiltro', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [
            'limiteRegistros' => $form->get('limiteRegistros')->getData()
        ];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltro')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(TteGuia::class)->lista($raw), "Guias");
            }
        }

        $arGuias = $paginator->paginate($em->getRepository(TteGuia::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('transporte/movimiento/transporte/guia/lista.html.twig', [
            'arGuias' => $arGuias,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/transporte/movimiento/transporte/guia/nuevo/{id}", name="transporte_movimiento_transporte_guia_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = new TteGuia();
        if ($id != 0) {
            $arGuia = $em->getRepository(TteGuia::class)->find($id);
        }
        $form = $this->createForm(GuiaType::class, $arGuia);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $resultado = $em->getRepository(TteGuia::class)->liquidar();
            if ($form->get('guardar')->isClicked()) {


                /*$txtCodigoCliente = $request->request->get('txtCodigoCliente');
                if ($txtCodigoCliente != '') {
                    $arCliente = $em->getRepository(TteCliente::class)->find($txtCodigoCliente);
                    if ($arCliente) {
                        if ($id == 0) {
                            $arGuia->setFechaIngreso(new \DateTime('now'));
                        }
//                        $arGuia->setClienteRel($arCliente);
//                        $arGuia->setOperacionIngresoRel($this->getUser()->getOperacionRel());
//                        $arGuia->setOperacionCargoRel($this->getUser()->getOperacionRel());
//                        $arGuia->setFactura($arGuia->getGuiaTipoRel()->getFactura());
//                        $arGuia->setCiudadOrigenRel($this->getUser()->getOperacionRel() ? $this->getUser()->getOperacionRel()->getCiudadRel() : null);
                        $em->persist($arGuia);
                        $em->flush();
                        return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_lista'));
                    } else {
                        Mensajes::error('No se encontro un cliente con el codigo ingresado');
                    }
                } else {
                    Mensajes::error('Debe de seleccionar un cliente');
                }*/

            }
        }
        return $this->render('transporte/movimiento/transporte/guia/nuevo.html.twig', ['arGuia' => $arGuia, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/transporte/movimiento/transporte/guia/detalle/{id}", name="transporte_movimiento_transporte_guia_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($id);
        if(!$arGuia) {
            Mensajes::error('La guia ingresada por url ' . $id . ' no existe');
            return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_lista'));

        }
        $form = Estandares::botonera($arGuia->getEstadoAutorizado(), $arGuia->getEstadoAprobado(), $arGuia->getEstadoAnulado());
        $form->add('btnRetirarNovedad', SubmitType::class, array('label' => 'Retirar'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnImprimir')->isClicked()) {
                $formato = new Guia();
                $formato->Generar($em, $id);
//                $respuesta = $em->getRepository(TteGuia::class)->imprimir($id);
//                if ($respuesta) {
//                    $em->flush();
//                    return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_detalle', array('id' => $id)));
//                }
            }
            if ($form->get('btnRetirarNovedad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(TteNovedad::class)->eliminar($arrSeleccionados);
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(TteGuia::class)->Anular($arGuia);
                return $this->redirect($this->generateUrl('transporte_movimiento_transporte_guia_detalle', ['id' => $id]));
            }
        }
        $arNovedades = $this->getDoctrine()->getRepository(TteNovedad::class)->guia($id);
        $arDespachoDetalles = $this->getDoctrine()->getRepository(TteDespachoDetalle::class)->guia($id);
        $arRedespachos = $this->getDoctrine()->getRepository(TteRedespacho::class)->guia($id);
        $arDesembarcos = $this->getDoctrine()->getRepository(TteDesembarco::class)->guia($id);
        $arFacturaDetalles = $this->getDoctrine()->getRepository(TteFacturaDetalle::class)->guia($id);
        $arRecibos = $this->getDoctrine()->getRepository(TteRecibo::class)->guia($id);
        return $this->render('transporte/movimiento/transporte/guia/detalle.html.twig', [
            'arGuia' => $arGuia,
            'arNovedades' => $arNovedades,
            'arDespachoDetalles' => $arDespachoDetalles,
            'arFacturaDetalles' => $arFacturaDetalles,
            'arRedespachos' => $arRedespachos,
            'arDesembarcos' => $arDesembarcos,
            'arRecibos'     => $arRecibos,
            'clase' => array('clase' => 'TteGuia', 'codigo' => $id),
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/trasnporte/guia/detalle/adicionar/novedad/{codigoGuia}/{codigoNovedad}", name="transporte_movimiento_transporte_guia_detalle_adicionar_novedad")
     */
    public function detalleAdicionarNovedad(Request $request, $codigoGuia, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
        $arNovedad = new TteNovedad();
        if ($codigoNovedad == 0) {
            $arNovedad->setEstadoAtendido(true);
            $arNovedad->setFechaReporte(new \DateTime('now'));
            $arNovedad->setFecha(new \DateTime('now'));
        } else {
            $arNovedad = $em->getRepository(TteNovedad::class)->find($codigoNovedad);
        }
        $form = $this->createForm(NovedadType::class, $arNovedad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arNovedad = $form->getData();
            $arNovedad->setGuiaRel($arGuia);
            $arNovedad->setUsuario($this->getUser()->getUserName());
            if ($codigoNovedad == 0) {
                $arNovedad->setFechaRegistro(new \DateTime('now'));
                $arNovedad->setFechaAtencion(new \DateTime('now'));
                $arNovedad->setFechaSolucion(new \DateTime('now'));
            }
            $arGuia->setEstadoNovedad(1);
            $em->persist($arGuia);
            $em->persist($arNovedad);
            $em->flush();

            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";

        }

        return $this->render('transporte/movimiento/transporte/guia/detalleAdicionarNovedad.html.twig', [
            'arGuia' => $arGuia,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/transporte/movimiento/transporte/novedad/solucion/{codigoNovedad}", name="transporte_movimiento_transporte_novedad_solucion")
     */
    public function novedadSolucion(Request $request, $codigoNovedad)
    {
        $em = $this->getDoctrine()->getManager();
        $arNovedad = $em->getRepository(TteNovedad::class)->find($codigoNovedad);
        $form = $this->createFormBuilder()
            ->add('solucion', TextareaType::class, array('label' => 'Solucion'))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arNovedad->setEstadoSolucion(1);
            $arNovedad->setSolucion($form->get('solucion')->getData());
            $arNovedad->setFechaSolucion(new \DateTime('now'));
            $em->persist($arNovedad);
            $arGuia = $em->getRepository(TteGuia::class)->find($arNovedad->getCodigoGuiaFk());
            $arGuia->setEstadoNovedad(0);
            $arGuia->setEstadoNovedadSolucion(1);
            $em->persist($arGuia);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('transporte/movimiento/transporte/guia/novedadSolucion.html.twig', array(
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoGuia' => $form->get('codigoGuiaPk')->getData(),
            'codigoDespacho' => $form->get('codigoDespachoFk')->getData(),
            'numero' => $form->get('numero')->getData(),
            'numeroFactura' => $form->get('numeroFactura')->getData(),
            'estadoDespachado' => $form->get('estadoDespachado')->getData(),
            'codigoCliente' => $form->get('codigoClienteFk')->getData(),
            'documentoCliente' => $form->get('documentoCliente')->getData(),
            'fechaIngresoDesde' => $form->get('fechaIngresoDesde')->getData() ?$form->get('fechaIngresoDesde')->getData()->format('Y-m-d'): null,
            'fechaIngresoHasta' => $form->get('fechaIngresoHasta')->getData() ?$form->get('fechaIngresoHasta')->getData()->format('Y-m-d'): null,
            'codigoFactura' => $form->get('codigoFacturaFk')->getData(),
            'estadoFacturado' => $form->get('estadoFacturado')->getData(),
            'estadoNovedad' => $form->get('estadoNovedad')->getData(),
            'estadoNovedadSolucion' => $form->get('estadoNovedadSolucion')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
            'nombreDestinatario' => $form->get('nombreDestinatario')->getData(),
            'remitente' => $form->get('remitente')->getData(),
        ];

        $arGuiaTipo = $form->get('codigoGuiaTipoFk')->getData();
        $arOperacionCargo = $form->get('codigoOperacionCargoFk')->getData();
        $arOperacionCargoIngreso = $form->get('codigoOperacionCargoIngreso')->getData();
        $arServicio = $form->get('codigoServicioFk')->getData();
        $arCiudadDestino = $form->get('codigoCiudadDestinoFk')->getData();

        if (is_object($arGuiaTipo)) {
            $filtro['guiaTipo'] = $arGuiaTipo->getCodigoGuiaTipoPk();
        }else{
            $filtro['guiaTipo'] =  $arGuiaTipo;
        }

        if (is_object($arOperacionCargo)) {
            $filtro['operacionCargo'] = $arOperacionCargo->getCodigoOperacionPk();
        }else{
            $filtro['operacionCargo'] = $arOperacionCargo;
        }

        if (is_object($arServicio)) {
            $filtro['servicio'] = $arServicio->getCodigoServicioPk();
        }else{
            $filtro['servicio'] = $arServicio;
        }

        if (is_object($arCiudadDestino)) {
            $filtro['ciudadDestino'] =  $arCiudadDestino->getCodigoCiudadPk();
        } else {
            $filtro['ciudadDestino'] = $arCiudadDestino;
        }

        if (is_object($arOperacionCargoIngreso)) {
            $filtro['operacionCargoIngreso'] =  $arOperacionCargoIngreso->getCodigoOperacionPk();
        } else {
            $filtro['operacionCargoIngreso'] = $arOperacionCargoIngreso;
        }


        return $filtro;

    }
}

