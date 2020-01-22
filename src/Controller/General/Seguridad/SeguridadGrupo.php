<?php


namespace App\Controller\General\Seguridad;


use App\Entity\General\GenModelo;
use App\Entity\Seguridad\SegGrupo;
use App\Entity\Seguridad\SegGrupoModelo;
use App\Entity\Seguridad\SegUsuarioModelo;
use App\Form\Type\General\GrupoType;
use App\General\General;
use App\Utilidades\Estandares;
use App\Utilidades\Mensajes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeguridadGrupo extends AbstractController
{
    /**
     * @Route("/general/seguridad/grupo/lista", name="general_seguridad_grupo_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $raw = [
            'filtros' => $session->get('filtroSegGrupo')
        ];
        $form = $this->createFormBuilder()
            ->add('codigoGrupoPk', TextType::class, array('required' => false, 'data' => $raw['filtros']['codigoGrupo']))
            ->add('nombre', TextType::class, array('required' => false, 'data' => $raw['filtros']['nombre']))
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
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $raw['filtros'] = $this->getFiltros($form);
                General::get()->setExportar($em->getRepository(SegGrupo::class)->lista($raw), "seguridad grupo");
            }
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->query->get('ChkSeleccionar');
                $em->getRepository(SegGrupo::class)->eliminar($arrSeleccionados);
            }
        }
        $arGrupos = $paginator->paginate($em->getRepository(SegGrupo::class)->lista($raw), $request->query->getInt('page', 1), 30);

        return $this->render('general/seguridad/grupo/lista.html.twig', [
            'arGrupos' => $arGrupos,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/general/seguridad/grupo/nuevo/{id}", name="general_seguridad_grupo_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arGrupo = new SegGrupo();
        if ($id != 0) {
            $arGrupo = $em->getRepository(SegGrupo::class)->find($id);
            if (!$arGrupo) {
                return $this->redirect($this->generateUrl('general_seguridad_grupo_lista'));
            }
        }
        $form = $this->createForm(GrupoType::class, $arGrupo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arEmbargoJusgado = $form->getData();
                $em->persist($arEmbargoJusgado);
                $em->flush();
                return $this->redirect($this->generateUrl('general_seguridad_grupo_detalle', array('id' => $arGrupo->getCodigoGrupoPK())));
            }
        }
        return $this->render('general/seguridad/grupo/nuevo.html.twig', [
            'arGrupo' => $arGrupo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/general/seguridad/grupo/detalle/{id}", name="general_seguridad_grupo_detalle")
     */
    public function detalle(Request $request, PaginatorInterface $paginator, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('btnElimarGrupoModelo', SubmitType::class, array('label' => 'Eliminar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnElimarGrupoModelo')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermiso');
                $em->getRepository(SegGrupoModelo::class)->eliminar($arrSeleccionados);
            }
        }
        $arGrupo = $em->getRepository(SegGrupo::class)->find($id);
        $arGrupoModelos = $em->getRepository(SegGrupoModelo::class)->findBy(['codigoGrupoFk' => $id]);
        return $this->render('general/seguridad/grupo/detalle.html.twig', [
            'form' =>$form->createView(),
            'arGrupo' => $arGrupo,
            'arGrupoModelos' => $arGrupoModelos
        ]);
    }

    public function getFiltros($form)
    {
        $session = new Session();

        $filtro = [
            'codigoGrupo' => $form->get('codigoGrupoPk')->getData(),
            'nombre' => $form->get('nombre')->getData(),
        ];
        $session->set('filtroSegGrupo', $filtro);

        return $filtro;

    }

    /**
     * @Route("/general/seguridad/grupo/nuevo/modelo/{id}/{codigoGrupo}", name="general_seguridad_grupo_nuevo")
     */
    public function nuevoModelo(Request $request, $id, $codigoGrupo)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('checkLista', CheckboxType::class, ['required' => false, 'label' => 'Lista'])
            ->add('checkDetalle', CheckboxType::class, ['required' => false, 'label' => 'Detalle'])
            ->add('checkNuevo', CheckboxType::class, ['required' => false, 'label' => 'Nuevo'])
            ->add('checkAutorizar', CheckboxType::class, ['required' => false, 'label' => 'Autorizar'])
            ->add('checkAprobar', CheckboxType::class, ['required' => false, 'label' => 'Aprobar'])
            ->add('checkAnular', CheckboxType::class, ['required' => false, 'label' => 'Anular'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arDatos = $form->getData();

                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigoModelo) {
                        $arGenModelo = $em->getRepository(GenModelo::class)->find($codigoModelo);
                        if ($arGenModelo) {
                            $arGrupoModelo = $em->getRepository(SegGrupoModelo::class)->findBy(
                                ['codigoGrupoFk' => $codigoGrupo,
                                    'codigoModeloFk' => $arGenModelo->getCodigoModeloPk()]);
                            if (!$arGrupoModelo) {
                                $arGrupoModelo = new SegGrupoModelo();
                                $arGrupoModelo->setCodigoModeloFk($arGenModelo->getCodigoModeloPk());
                                $arGrupoModelo->setCodigoGrupoFk($codigoGrupo);
                                $arGrupoModelo->setLista($arDatos['checkLista']);
                                $arGrupoModelo->setDetalle($arDatos['checkDetalle']);
                                $arGrupoModelo->setNuevo($arDatos['checkNuevo']);
                                $arGrupoModelo->setAutorizar($arDatos['checkAutorizar']);
                                $arGrupoModelo->setAprobar($arDatos['checkAprobar']);
                                $arGrupoModelo->setAnular($arDatos['checkAnular']);
                                $em->persist($arGrupoModelo);
                            }
                        } else {
                            Mensajes::error("el modelos no existe");
                        }
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    Mensajes::error("no hay modelos seleccionados");
                }
            }
        }

        $arGenModelos = $em->getRepository(GenModelo::class)->findAll();
        return $this->render('general/seguridad/grupo/nuevoModelo.html.twig', [
            'form' => $form->createView(),
            'arGenModelos' => $arGenModelos,

        ]);
    }

    /**
     * @Route("/general/seguridad/grupo/editar/modelo/{codigoModelo}", name="general_seguridad_grupo_editar")
     */
    public function editarPermisos(Request $request, $codigoModelo)
    {
        $em = $this->getDoctrine()->getManager();
        if ($codigoModelo != 0) {
            $arGrupoModelo = $em->getRepository(SegGrupoModelo::class)->find($codigoModelo);
            $form = $this->createFormBuilder()
                ->add('checkLista', CheckboxType::class, ['required' => false, 'label' => 'Lista', 'data' => $arGrupoModelo->getLista()])
                ->add('checkDetalle', CheckboxType::class, ['required' => false, 'label' => 'Detalle', 'data' => $arGrupoModelo->getDetalle()])
                ->add('checkNuevo', CheckboxType::class, ['required' => false, 'label' => 'Nuevo', 'data' => $arGrupoModelo->getNuevo()])
                ->add('checkAutorizar', CheckboxType::class, ['required' => false, 'label' => 'Autorizar', 'data' => $arGrupoModelo->getAutorizar()])
                ->add('checkAprobar', CheckboxType::class, ['required' => false, 'label' => 'Aprobar', 'data' => $arGrupoModelo->getAprobar()])
                ->add('checkAnular', CheckboxType::class, ['required' => false, 'label' => 'Anular', 'data' => $arGrupoModelo->getAnular()])
                ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('btnGuardar')->isClicked()) {
                    if ($arGrupoModelo) {
                        $arGrupoModelo->setLista($form->get('checkLista')->getData());
                        $arGrupoModelo->setDetalle($form->get('checkDetalle')->getData());
                        $arGrupoModelo->setNuevo($form->get('checkNuevo')->getData());
                        $arGrupoModelo->setAutorizar($form->get('checkAutorizar')->getData());
                        $arGrupoModelo->setAprobar($form->get('checkAprobar')->getData());
                        $arGrupoModelo->setAnular($form->get('checkAnular')->getData());
                        $em->persist($arGrupoModelo);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                }

            }
        }
        return $this->render('general/seguridad/grupo/editarModelo.html.twig', [
            'form' => $form->createView(),
            'arGrupoModelo'=>$arGrupoModelo
        ]);

    }
}