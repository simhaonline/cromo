<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Solicitud;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuAspirante;
use App\Entity\RecursoHumano\RhuSolicitud;
use App\Entity\RecursoHumano\RhuSolicitudAspirante;
use App\Entity\Transporte\TteMonitoreo;
use App\Form\Type\RecursoHumano\AspiranteType;
use App\Form\Type\RecursoHumano\SolicitudType;
use App\General\General;
use App\Utilidades\Estandares;
use function Complex\add;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SolicitudController extends MaestroController
{
    public $tipo = "Movimiento";
    public $modelo = "RhuSolicitud";

    protected $clase = RhuSolicitud::class;
    protected $claseFormulario = SolicitudType::class;
    protected $claseNombre = "RhuSolicitud";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Solicitud";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/solicitud/lista", name="recursohumano_movimiento_seleccion_solicitud_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSolicitudPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, ['required' => false])
            ->add('estadoAutorizado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAprobado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('estadoAnulado', ChoiceType::class, ['choices' => ['TODOS' => '', 'SI' => '1', 'NO' => '0'], 'required' => false])
            ->add('limiteRegistros', TextType::class, array('required' => false, 'data' => 100))
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
                General::get()->setExportar($em->getRepository(RhuSolicitud::class)->lista($raw), "Solicitudes");
            }
        }
        $arSolicitudes = $paginator->paginate($em->getRepository(RhuSolicitud::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seleccion/solicitud/lista.html.twig', [
            'arSolicitudes' => $arSolicitudes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/solicitud/nuevo/{id}", name="recursohumano_movimiento_seleccion_solicitud_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSolicitud = new RhuSolicitud();
        if ($id != 0) {
            $arSolicitud = $em->getRepository('App:RecursoHumano\RhuSolicitud')->find($id);
            if (!$arSolicitud) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'solicitud']));
            }
        }
        $form = $this->createForm(SolicitudType::class, $arSolicitud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSolicitud->setFecha(new \DateTime('now'));
                $arrControles = $request->request->All();
                if (isset($arrControles["tipo"])) {
                    switch ($arrControles["tipo"]) {
                        case "salarioFijo":
                            $arSolicitud->setSalarioFijo(1);
                            $arSolicitud->setSalarioVariable(0);
                            break;
                        case "salarioVariable":
                            $arSolicitud->setSalarioFijo(0);
                            $arSolicitud->setSalarioVariable(1);
                            break;
                    }
                }
                $em->persist($arSolicitud);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_solicitud_detalle', array('id' => $arSolicitud->getCodigoSolicitudPk())));

            }
        }
        return $this->render('recursohumano/movimiento/seleccion/solicitud/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arSolicitud
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/solicitud/detalle/{id}", name="recursohumano_movimiento_seleccion_solicitud_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id != 0) {
            $arSolicitud = $em->getRepository(RhuSolicitud::class)->find($id);
            $arrGenerarSeleccion = ['attr' => ['class' => 'btn btn-sm btn-default'], 'label' => 'Generar seleccion', 'disabled' => false];
            $form = Estandares::botonera($arSolicitud->getEstadoAutorizado(), $arSolicitud->getEstadoAprobado(), $arSolicitud->getEstadoAnulado());
            $form->add('btnGenerar', SubmitType::class, $arrGenerarSeleccion);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->get('btnAutorizar')->isClicked()) {
                    $em->getRepository(RhuSolicitud::class)->autorizar($arSolicitud);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_solicitud_detalle', ['id' => $id]));
                }
                if ($form->get('btnDesautorizar')->isClicked()) {
                    $em->getRepository(RhuSolicitud::class)->desautorizar($arSolicitud);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_solicitud_detalle', ['id' => $id]));
                }
                if ($form->get('btnGenerar')->isClicked()) {
                    $arAspirantesSeleccionados = $request->request->get('ChkSeleccionar');
                    $em->getRepository(RhuSolicitudAspirante::class)->aprobarDetallesSeleccionados($arAspirantesSeleccionados);
                    return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_solicitud_detalle', ['id' => $id]));
                }
            }
        }
        $arAspirantes = $em->getRepository(RhuSolicitudAspirante::class)->lista($raw = null);
        return $this->render('recursohumano/movimiento/seleccion/solicitud/detalle.html.twig', [
            'arAspirantes' => $arAspirantes,
            'arSolicitud' => $arSolicitud,
            'form' => $form->createView()
        ]);
    }

//    /**
//     * @Route("/recursohumano/movimiento/seleccion/solicitud/buscar/aspirante/{id}", name="recursohumano_movimiento_seleccion_solicitud_detalle")
//     */
//    public function buscarAspiranteAction(Request $request, $id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $paginator = $this->get('knp_paginator');
//        $arRequisicion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
//        $arRequisicion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($id);
//        $form = $this->formularioBuscarAspirante();
//        $form->handleRequest($request);
//        $this->listarAspirantes();
//        if ($form->isValid()) {
//            if ($form->get('BtnFiltrar')->isClicked()) {
//                $this->filtrarBuscarAspirante($form, $request);
//                $this->listarAspirantes();
//            }
//            if ($form->get('BtnAplicar')->isClicked()) {
//                $arrSeleccionados = $request->request->get('ChkSeleccionar');
//                if ($arRequisicion->getEstadoAnulado() != 1) {
//                    if ($arrSeleccionados) {
//                        foreach ($arrSeleccionados AS $codigo) {
//                            $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigo);
//                            $arRequisicionAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
//                            $arRequisicionAspirante->setSeleccionRequisitoRel($arRequisicion);
//                            $arRequisicionAspirante->setAspiranteRel($arAspirante);
//                            $em->persist($arRequisicionAspirante);
//                        }
//                        $em->flush();
//                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
//                    }
//                } else {
//                    $objMensaje->Mensaje("error", "No se pueden aplicar mas aspirantes,la requisicion ha sido anulada");
//                }
//
//            }
//        }
//        $arAspirantes = $paginator->paginate($em->createQuery($this->strDqlListaAspirantes), $request->query->get('page', 1), 100);
//        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Requisicion:buscarAspirante.html.twig', array(
//            'arRequisicion' => $arRequisicion,
//            'arAspirantes' => $arAspirantes,
//            'form' => $form->createView()
//        ));
//    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSolicitud' => $form->get('codigoSolicitudPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}