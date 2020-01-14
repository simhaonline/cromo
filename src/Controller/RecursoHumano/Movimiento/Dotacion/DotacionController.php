<?php


namespace App\Controller\RecursoHumano\Movimiento\Dotacion;


use App\Controller\BaseController;
use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuDotacion;
use App\Entity\RecursoHumano\RhuDotacionDetalle;
use App\Entity\RecursoHumano\RhuDotacionElemento;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Form\Type\RecursoHumano\DotacionElementoType;
use App\Form\Type\RecursoHumano\DotacionType;
use App\Form\Type\RecursoHumano\EmpleadoType;
use App\General\General;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DotacionController extends AbstractController
{
    protected $clase = RhuDotacion::class;
    protected $claseFormulario = DotacionType::class;
    protected $claseNombre = "RhuDotacion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "movimiento";
    protected $grupo = "Dotacion";
    protected $nombre = "Dotacion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/moviento/dotacion/empleado/lista", name="recursohumano_movimiento_dotacion_dotacion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros'=> $session->get('filtroRhuDotacion')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoDotacionPk', TextType::class, array('required' => false,'data'=>$raw['filtros']['codigoDotacion']))
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false,'data'=>$raw['filtros']['codigoEmpleado']))
            ->add('codigoInternoReferencia', TextType::class, array('required' => false,'data'=>$raw['filtros']['codigoInternoReferencia']))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaDesde']?date_create($raw['filtros']['fechaDesde']):null ])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data'=>$raw['filtros']['fechaHasta']?date_create($raw['filtros']['fechaHasta']):null ])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoAutorizado'] ])
            ->add('estadoCerrado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoCerrado']])
            ->add('estadoSalidaInventario', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false, 'data'=>$raw['filtros']['estadoSalidaInventario']])
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw['limiteRegistros'] = $form->get('limiteRegistros')->getData();
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuDotacion::class)->lista($raw)->getQuery()->execute(), "Dotaciones");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuDotacion::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_dotacion_dotacion_lista'));
            }
        }
        $arDotaciones = $paginator->paginate($em->getRepository(RhuDotacion::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/dotacion/dotacion/lista.html.twig', [
            'arDotaciones' => $arDotaciones,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/moviento/dotacion/dotacion/nuevo/{id}", name="recursohumano_movimiento_dotacion_dotacion_nuevo")
     */
    public function nuevo(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacion = new RhuDotacion();
        if ($id != 0) {
            $arDotacion = $em->getRepository(RhuDotacion::class)->find($id);
        }
        $form = $this->createForm(DotacionType::class, $arDotacion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $txtCodigoEmpleado = $request->request->get('txtCodigoEmpleado');

                if($txtCodigoEmpleado != ""){
                    $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($txtCodigoEmpleado);
                    if ($arEmpleado){
                        $arDotacion->setFecha(new \DateTime('now'));
                        $arDotacion->setEmpleadoRel($arEmpleado);
                        $arDotacion->setCodigoUsuario($this->getUser()->getUserName());
                        $em->persist($arDotacion);
                        $em->flush();
                        return $this->redirect($this->generateUrl('recursohumano_movimiento_dotacion_dotacion_detalle', ['id' => $arDotacion->getCodigoDotacionPk()]));

                    }else {
                        Mensajes::error('Debe seleccionar un empleado');
                    }
                }
            }
        }
        return $this->render('recursohumano/movimiento/dotacion/dotacion/nuevo.html.twig',[
            'form' => $form->createView(),
            'arDotacion' => $arDotacion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/moviento/dotacion/dotacion/detalle/{id}", name="recursohumano_movimiento_dotacion_dotacion_detalle")
     */
    public function detalle(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacion = $em->getRepository(RhuDotacion::class)->find($id);
        $arDotacionDetalle = $em->getRepository(RhuDotacionDetalle::class)->findBy(['codigoDotacionFk'=>$id]);
        $form = $this->createFormBuilder()
            ->add('btnAutorizar', SubmitType::class, ['label' => 'Autorizar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                if (count($arDotacionDetalle) > 0){
                    if ( $arDotacion->getEstadoAutorizado() != true){
                        $arDotacion->setEstadoAutorizado(1);
                        $em->persist($arDotacion);
                        $em->flush();
                    }else{
                        Mensajes::info("La dotación ya esta autorizada");
                    }
                }else{
                    Mensajes::warning("No se puede autorizar una dotación sin elementos");
                }
            }
        }
        return $this->render('recursohumano/movimiento/dotacion/dotacion/detalle.html.twig',[
            'arDotacion'=>$arDotacion,
            'arDotacionDetalle'=>$arDotacionDetalle,
            'form'=>$form->createView()
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("recursohumano/moviento/dotacion/elemento/nuevo/{id}", name="recursohumano_movimiento_dotacion_elemento_nuevo")
     */
    public function nuevoElemento(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $arDotacionElemento =  new RhuDotacionElemento();
        $form = $this->createForm(DotacionElementoType::class, $arDotacionElemento);
        $form->handleRequest($request);
        if ($id != 0) {
            $arDotacionElemento = $em->getRepository(RhuDotacionElemento::class)->find($id);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $em->persist($arDotacionElemento);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }else{
                Mensajes::error('Debe se puede reguistrar el elemento');
            }

        }
        return $this->render('recursohumano/movimiento/dotacion/dotacion/nuevoElemento.html.twig', [
            'form' => $form->createView(),
            'arDotacionElemento' => $arDotacionElemento
        ]);
    }

    /**
    * @param Request $request
    * @param $id
    * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    * @Route("recursohumano/moviento/dotacion/detalle/nuevo/{id}/{codigoDotacion}", name="recursohumano_movimiento_dotacion_detalle_nuevo")
    */
    public function nuevoDetalleDotacion(Request $request, $id, $codigoDotacion){
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $form = $this-> createFormBuilder()
            ->add('clave', TextType::class,['required' => false, 'data' => $session->get('filtroClave')])
            ->add('nombre', TextType::class,['required' => false, 'data' => $session->get('filtroNombre')])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrCantidad = $request->request->get('arrCantidad');
                $arDotacion = $em->getRepository(RhuDotacion::class)->find($codigoDotacion);
                foreach ($arrCantidad as $elemento => $cantidad){
                    $arDotacionElemento = $em->getRepository(RhuDotacionElemento::class)->find($elemento);
                    if ($arDotacionElemento && $cantidad > 0){
                        $arDetalleDotacion= new RhuDotacionDetalle();
                        $arDetalleDotacion->setCantidadAsignada($cantidad);
                        $arDetalleDotacion->setCantidadDevuelta(0);
                        $arDetalleDotacion->setDotacionElementoRel($arDotacionElemento);
                        $arDetalleDotacion->setDotacionRel($arDotacion);
                        $em->persist($arDetalleDotacion);
                        $em->flush();
                    }
                }
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }else{
                Mensajes::error('Debe se puede reguistrar el elemento');
            }
            if ($form->get('btnFiltrar')){
                $session->set('filtroClave', $form->get('clave')->getData());
                $session->set('filtroNombre', $form->get('nombre')->getData());
            }

        }
        $arDotacionElementos = $paginator->paginate($em->getRepository(RhuDotacionElemento::class)->lista(),$request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/dotacion/dotacion/nuevoDetalleDotacion.html.twig', [
            'form' => $form->createView(),
            'arDotacionElementos' => $arDotacionElementos
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();
        $filtro = [
            'codigoDotacion' => $form->get('codigoDotacionPk')->getData(),
            'codigoInternoReferencia' => $form->get('codigoInternoReferencia')->getData(),
            'codigoEmpleado' => $form->get('codigoEmpleadoFk')->getData(),
            'fechaDesde' => $form->get('fechaDesde')->getData() ? $form->get('fechaDesde')->getData()->format('Y-m-d') : null,
            'fechaHasta' => $form->get('fechaHasta')->getData() ? $form->get('fechaHasta')->getData()->format('Y-m-d') : null,
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoCerrado' => $form->get('estadoCerrado')->getData(),
            'estadoSalidaInventario' => $form->get('estadoSalidaInventario')->getData(),
        ];

        $session->set('filtroRhuDotacion', $filtro);
        return $filtro;
    }

}