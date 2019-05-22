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
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DotacionController extends BaseController
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
    public function lista(Request $request){
        $this->request = $request;
        $em = $this->getDoctrine()->getManager();
        $formBotonera = $this->botoneraLista();
        $formBotonera->handleRequest($request);
        $formFiltro = $this->getFiltroLista();
        $formFiltro->handleRequest($request);

        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            if ($formFiltro->get('btnFiltro')->isClicked()) {
                FuncionesController::generarSession($this->modulo, $this->nombre, $this->claseNombre, $formFiltro);
            }
        }
        $datos = $this->getDatosLista(true);
        return $this->render('recursohumano/movimiento/dotacion/dotacion/lista.html.twig', [
            'arrDatosLista' =>  $datos,
            'formBotonera' => $formBotonera->createView(),
            'formFiltro' => $formFiltro->createView()
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
            ->add('cantidad', TextType::class,['required' => false, 'data' => $session->get('filtroNombre')])
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

        }
        $arDotacionElementos = $paginator->paginate($em->getRepository(RhuDotacionElemento::class)->lista(),$request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/movimiento/dotacion/dotacion/nuevoDetalleDotacion.html.twig', [
            'form' => $form->createView(),
            'arDotacionElementos' => $arDotacionElementos
        ]);
    }

}