<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion\Seleccion;


use App\Controller\BaseController;
use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\Estructura\FuncionesController;
use App\Controller\MaestroController;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionAntecedente;
use App\Entity\RecursoHumano\RhuSeleccionComentario;
use App\Entity\RecursoHumano\RhuSeleccionEntrevista;
use App\Entity\RecursoHumano\RhuSeleccionPrueba;
use App\Entity\RecursoHumano\RhuSeleccionReferencia;
use App\Entity\RecursoHumano\RhuSeleccionVisita;
use App\Form\Type\RecursoHumano\SeleccionAntecedenteType;
use App\Form\Type\RecursoHumano\SeleccionComentarioType;
use App\Form\Type\RecursoHumano\SeleccionEntrevistaType;
use App\Form\Type\RecursoHumano\SeleccionPruebaType;
use App\Form\Type\RecursoHumano\SeleccionReferenciaType;
use App\Form\Type\RecursoHumano\SeleccionType;
use App\Form\Type\RecursoHumano\SeleccionVisitaType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SeleccionController extends MaestroController
{

    public $tipo = "movimiento";
    public $modelo = "RhuSeleccion";

    protected $clase = RhuSeleccion::class;
    protected $claseFormulario = SeleccionType::class;
    protected $claseNombre = "RhuSeleccion";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Movimiento";
    protected $grupo = "Seleccion";
    protected $nombre = "Seleccion";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("recursohumano/movimiento/seleccion/seleccion/lista", name="recursohumano_movimiento_seleccion_seleccion_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoSeleccionPk', TextType::class, array('required' => false))
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
                General::get()->setExportar($em->getRepository(RhuSeleccion::class)->lista($raw), "Selecciones");
            }
        }
        $arSelecciones = $paginator->paginate($em->getRepository(RhuSeleccion::class)->lista($raw), $request->query->getInt('page', 1), 30);
        return $this->render('recursohumano/movimiento/seleccion/seleccion/lista.html.twig', [
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/nuevo/{id}", name="recursohumano_movimiento_seleccion_seleccion_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = new RhuSeleccion();
        if ($id != 0) {
            $arSeleccion = $em->getRepository('App:RecursoHumano\RhuSeleccion')->find($id);
            if (!$arSeleccion) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'seleccion']));
            }
        }
        $form = $this->createForm(SeleccionType::class, $arSeleccion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arSeleccion->setFecha(new \DateTime('now'));
                $em->persist($arSeleccion);
                $em->flush();
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_detalle', ['id' => $arSeleccion->getCodigoSeleccionPk()]));
            }
            /*if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSeleccion);
                $em->flush($arSeleccion);
                return $this->redirect($this->generateUrl('recursoHumano_movimiento_seleccion_seleccion_nuevo', ['codigoSeleccion' => 0]));
            }*/
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/nuevo.html.twig', [
            'form' => $form->createView(), 'arSeleccion' => $arSeleccion
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("recursohumano/movimiento/seleccion/seleccion/detalle/{id}", name="recursohumano_movimiento_seleccion_seleccion_detalle")
     */
    public function detalle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($id);
        if ($id != 0) {
            if (!$arSeleccion) {
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_lista'));
            }
        }
        $arrEliminarEntrevista = ['attr' => ['class' => 'btn btn-sm btn-danger'], 'label' => 'Eliminar', 'disabled' => false];
        $form = Estandares::botonera($arSeleccion->getEstadoAutorizado(), $arSeleccion->getEstadoAprobado(), $arSeleccion->getEstadoAnulado());
        $form->add('btnEliminarEntrevista', SubmitType::class, $arrEliminarEntrevista);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnAutorizar')->isClicked()) {
                $em->getRepository(RhuSeleccion::class)->autorizar($arSeleccion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_detalle', ['id' => $id]));
            }
            if ($form->get('btnDesautorizar')->isClicked()) {
                $em->getRepository(RhuSeleccion::class)->desautorizar($arSeleccion);
                return $this->redirect($this->generateUrl('recursohumano_movimiento_seleccion_seleccion_detalle', ['id' => $id]));
            }
            if ($form->get('btnAnular')->isClicked()) {
                $em->getRepository(RhuSeleccion::class)->anular($arSeleccion);
            }
        }
        $arSeleccionEntrevistas = $em->getRepository(RhuSeleccionEntrevista::class)->findBy(array('codigoSeleccionFk' => $id));
        $arSeleccionReferencias = $em->getRepository(RhuSeleccionReferencia::class)->findBy(array('codigoSeleccionFk' => $id));
        $arSeleccionPruebas = $em->getRepository(RhuSeleccionPrueba::class)->findBy(array('codigoSeleccionFk' => $id));
        $arSeleccionAntecedentes = $em->getRepository(RhuSeleccionAntecedente::class)->findBy(array('codigoSeleccionFk' => $id));
        $arSeleccionVisitas = $em->getRepository(RhuSeleccionVisita::class)->findBy(array('codigoSeleccionFk' => $id));
        $arSeleccionComentarios = $em->getRepository(RhuSeleccionComentario::class)->findBy(array('codigoSeleccionFk' => $id));
        return $this->render('recursohumano/movimiento/seleccion/seleccion/detalle.html.twig', [
            'arSeleccionEntrevistas' => $arSeleccionEntrevistas,
            'arSeleccionReferencias' => $arSeleccionReferencias,
            'arSeleccionPruebas' => $arSeleccionPruebas,
            'arSeleccionAntecedentes' => $arSeleccionAntecedentes,
            'arSeleccionVisitas' => $arSeleccionVisitas,
            'arSeleccionComentarios' => $arSeleccionComentarios,
            'arSeleccion' => $arSeleccion,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/agregar/entrevista/{codigoSeleccion}/{codigoSeleccionEntrevista}", name="recursohumano_movimiento_seleccion_seleccion_agregar_entrevista")
     */
    public function agregarEntrevista(Request $request, $codigoSeleccion, $codigoSeleccionEntrevista)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
        $arSeleccionEntrevista = new RhuSeleccionEntrevista();
        if ($codigoSeleccionEntrevista != 0) {
            $arSeleccionEntrevista = $em->getRepository(RhuSeleccionEntrevista::class)->find($codigoSeleccionEntrevista);
        }
        $form = $this->createForm(SeleccionEntrevistaType::class, $arSeleccionEntrevista);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arSeleccion->getEstadoAutorizado() == 0) {
                if ($form->get('seleccionEntrevistaTipoRel')->getData() == null) {
                    Mensajes::error("Debe selecionar un tipo de entrevista");
                } else {
                    $arSeleccionEntrevista = $form->getData();
                    $arSeleccionEntrevista->setSeleccionRel($arSeleccion);
                    $em->persist($arSeleccionEntrevista);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/entrevista.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/agregar/prueba/{codigoSeleccion}/{codigoSeleccionPrueba}", name="recursohumano_movimiento_seleccion_seleccion_agregar_prueba")
     */
    public function agregarPruebaAction(Request $request, $codigoSeleccion, $codigoSeleccionPrueba)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
        $arSeleccionPrueba = New RhuSeleccionPrueba();
        if ($codigoSeleccionPrueba != 0) {
            $arSeleccionPrueba = $em->getRepository(RhuSeleccionPrueba::class)->find($codigoSeleccionPrueba);
        }
        $form = $this->createForm(SeleccionPruebaType::class, $arSeleccionPrueba);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arSeleccion->getEstadoAutorizado() == 0) {
                $arSeleccionPrueba = $form->getData();
                $arSeleccionPrueba->setSeleccionRel($arSeleccion);
                $arSeleccion->setFechaPrueba($arSeleccionPrueba->getFecha());
                $em->persist($arSeleccion);
                $em->persist($arSeleccionPrueba);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/prueba.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/agregar/visita/{codigoSeleccion}/{codigoSeleccionVisita}", name="recursohumano_movimiento_seleccion_seleccion_agregar_visita")
     */
    public function agregarVisitaAction(Request $request, $codigoSeleccion, $codigoSeleccionVisita)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
        $arSeleccionVisita = new RhuSeleccionVisita();
        if ($codigoSeleccionVisita != 0) {
            $arSeleccionVisita = $em->getRepository(RhuSeleccionVisita::class)->find($codigoSeleccionVisita);
        }
        $form = $this->createForm(SeleccionVisitaType::class, $arSeleccionVisita);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arSeleccion->getEstadoAutorizado() == 0) {
                $arSeleccionVisita = $form->getData();
                $arSeleccionVisita->setSeleccionRel($arSeleccion);
                $arSeleccionVisita->setFecha($form->get("fecha")->getData());
                $em->persist($arSeleccionVisita);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/visita.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/agregar/referencia/{codigoSeleccion}/{codigoSeleccionReferencia}", name="recursohumano_movimiento_seleccion_seleccion_agregar_referencia")
     */
    public function agregarReferenciaAction(Request $request, $codigoSeleccion, $codigoSeleccionReferencia)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
        $arSeleccionReferencia = new RhuSeleccionReferencia();
        if ($codigoSeleccionReferencia != 0) {
            $arSeleccionReferencia = $em->getRepository(RhuSeleccionReferencia::class)->find($codigoSeleccionReferencia);
        }
        $form = $this->createForm(SeleccionReferenciaType::class, $arSeleccionReferencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arSeleccion->getEstadoAutorizado() == 0) {
                $arSeleccionReferencia = $form->getData();
                $arSeleccionReferencia->setSeleccionRel($arSeleccion);
                $em->persist($arSeleccionReferencia);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/referencia.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/agregar/antecedente/{codigoSeleccion}/{codigoSeleccionAntecedente}", name="recursohumano_movimiento_seleccion_seleccion_agregar_antecedente")
     */
    public function agregarAntecedenteAction(Request $request, $codigoSeleccion, $codigoSeleccionAntecedente)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
        $arSeleccionAntecedente = New RhuSeleccionAntecedente();
        if ($codigoSeleccionAntecedente != 0) {
            $arSeleccionAntecedente = $em->getRepository(RhuSeleccionAntecedente::class)->find($codigoSeleccionAntecedente);
        }
        $form = $this->createForm(SeleccionAntecedenteType::class, $arSeleccionAntecedente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($arSeleccion->getEstadoAutorizado() == 0) {
                $arSeleccionAntecedente->setSeleccionRel($arSeleccion);
                $em->persist($arSeleccionAntecedente);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('recursohumano/movimiento/seleccion/seleccion/antecedente.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("recursohumano/movimiento/seleccion/seleccion/agregar/comentarios/{codigoSeleccion}/{codigoSeleccionComentario}", name="recursohumano_movimiento_seleccion_seleccion_agregar_comentario")
     */
    public function agregarComentarios(Request $request, $codigoSeleccion)
    {
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = $em->getRepository(RhuSeleccion::class)->find($codigoSeleccion);
        $form = $this->createForm(SeleccionComentarioType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arComentario = new RhuSeleccionComentario();
            $arComentario->setSeleccionRel($arSeleccion);
            $arComentario->setFecha($form->get("fecha")->getData());
            $arComentario->setComentarios($form->get("comentarios")->getData());
            $em->persist($arComentario);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render(
            'recursohumano/movimiento/seleccion/seleccion/comentario.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoSeleccion' => $form->get('codigoSeleccionPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
            'estadoAutorizado' => $form->get('estadoAutorizado')->getData(),
            'estadoAprobado' => $form->get('estadoAprobado')->getData(),
            'estadoAnulado' => $form->get('estadoAnulado')->getData(),
        ];

        return $filtro;

    }
}

