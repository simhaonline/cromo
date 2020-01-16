<?php

namespace App\Controller\RecursoHumano\Administracion\Examen;


use App\Controller\Estructura\FuncionesController;
use App\Entity\RecursoHumano\RhuEntidadExamen;
use App\Entity\RecursoHumano\RhuEntidadExamenDetalle;
use App\Entity\RecursoHumano\RhuExamenListaPrecio;
use App\Entity\RecursoHumano\RhuExamenTipo;
use App\Form\Type\RecursoHumano\ExamenEntidadType;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class EntidadExamenController extends AbstractController
{
    protected $clase = RhuEntidadExamen::class;
    protected $claseFormulario = ExamenEntidadType::class;
    protected $claseNombre = "RhuEntidadExamen";
    protected $modulo = "RecursoHumano";
    protected $funcion = "Administracion";
    protected $grupo = "Examen";
    protected $nombre = "EntidadExamen";

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/administracion/examen/entidadexamen/lista", name="recursohumano_administracion_examen_entidadexamen_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoEntidadExamenPk', TextType::class, array('required' => false))
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
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
            }
            if ($form->get('btnExcel')->isClicked()) {
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(RhuEntidadExamen::class)->lista($raw), "Examenes");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(RhuEntidadExamen::class)->eliminar($arrSeleccionados);
            }
        }
        $arEntidadExamenes = $paginator->paginate($em->getRepository(RhuEntidadExamen::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('recursohumano/administracion/examen/entidadexamen/lista.html.twig', [
            'arEntidadExamenes' => $arEntidadExamenes,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/recursohumano/administracion/entidadexamen/examen/nuevo/{id}", name="recursohumano_administracion_examen_entidadexamen_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $arEntidadExamen = new RhuEntidadExamen();
        if ($id != '0') {
            $arEntidadExamen = $em->getRepository(RhuEntidadExamen::class)->find($id);
            $arEntidadExamen->setUsuario($this->getUser()->getUserName());
            if (!$arEntidadExamen) {
                return $this->redirect($this->generateUrl('recursohumano_administracion_examen_entidadexamen_lista'));
            }
        } else {
            $arEntidadExamen->setUsuario($this->getUser()->getUserName());
        }

        $form = $this->createForm(ExamenEntidadType::class, $arEntidadExamen);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // guardar la tarea en la base de datos
            if ($form->get('guardar')->isClicked()) {
                if ($id == 0) {
                    $arEntidadExamen->setUsuario($this->getUser()->getUserName());
                }
                $em->persist($arEntidadExamen);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('recursohumano_administracion_examen_entidadexamen_lista'));
        }
        return $this->render('recursohumano/administracion/examen/entidadexamen/nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/recursohumano/administracion/examen/entidadexamen/detalle/{id}", name="recursohumano_administracion_examen_entidadexamen_detalle")
     */
    public function detalle(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $arEntidadExamenes = $em->getRepository(RhuEntidadExamen::class)->find($id);
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
            return $this->redirect($this->generateUrl('recursohumano_administracion_examen_entidadexamen_detalle', array('id' => $id)));
        }
        $arEntidadExamenDetalle = $em->getRepository(RhuExamenListaPrecio::class)->findBy(array('codigoEntidadExamenFk' => $id));
        return $this->render('recursohumano/administracion/examen/entidadexamen/detalle.html.twig', array(
            'arEntidadExamenes' => $arEntidadExamenes,
            'arEntidadExamenDetalle' => $arEntidadExamenDetalle,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/recursohumano/administracion/examen/entidadexamen/detalle/nuevo/{codigoEntidadExamenPk}", name="recursohumano_administracion_examen_entidadexamen_detalle_nuevo")
     */
    public function detalleNuevo(Request $request, $codigoEntidadExamenPk)
    {
        $em = $this->getDoctrine()->getManager();
        $arEntidadExamen = $em->getRepository(RhuEntidadExamen::class)->find($codigoEntidadExamenPk);
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
        return $this->render('recursohumano/administracion/examen/entidadexamen/detalleNuevo.html.twig', array(
            'arEntidadExamen' => $arEntidadExamen,
            'arExamenTipos' => $arExamenTipos,
            'form' => $form->createView()));
    }

    public function getFiltros($form)
    {
        $filtro = [
            'codigoEntidadExamen' => $form->get('codigoEntidadExamenPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];

        return $filtro;

    }
}
