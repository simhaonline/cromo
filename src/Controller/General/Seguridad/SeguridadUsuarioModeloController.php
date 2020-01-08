<?php

namespace App\Controller\General\Seguridad;

use App\Entity\General\GenModulo;
use App\Entity\General\GenSegmento;
use App\Entity\Seguridad\SegUsuarioModelo;
use App\Entity\Seguridad\SegUsuarioProceso;
use App\Entity\Seguridad\SegUsuarioSegmento;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeguridadUsuarioModeloController extends AbstractController
{
    protected $clase = SegUsuarioModelo::class;
    protected $claseNombre = "SegUsuarioModelo";
    protected $modulo = "General";
    protected $funcion = "Seguridad";
    protected $grupo = "Seguridad";
    protected $nombre = "SegUsuarioModelo";

    /**
     * @Route("/general/seguridad/usuario/modelo/lista/{hash}", name="general_seguridad_usuario_modelo_lista")
     */
    public function lista($hash, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermiso');
                if ($arrSeleccionados && count($arrSeleccionados) > 0) {
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados as $codigoSeguridad) {
                            $arSegUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->find($codigoSeguridad);
                            if ($arSegUsuarioModelo) {
                                $em->remove($arSegUsuarioModelo);
                            }

                        }
                        $em->flush();
                        return $this->redirectToRoute('general_seguridad_usuario_modelo_lista', array('hash' => $hash));
                    }
                }
            }
        }
        $formProcesos = $this->createFormBuilder()
            ->add('btnEliminarProceso', SubmitType::class, ['label' => 'Eliminar'])
            ->getForm();
        $formProcesos->handleRequest($request);
        if ($formProcesos->isSubmitted() && $formProcesos->isValid()) {
            if ($formProcesos->get('btnEliminarProceso')->isClicked()) {
                $arrSeleccionadosProceso = $request->request->get('ChkSeleccionarPermisoProcesos');
                if ($arrSeleccionadosProceso && count($arrSeleccionadosProceso) > 0) {
                    if ($arrSeleccionadosProceso) {
                        foreach ($arrSeleccionadosProceso as $codigoProceso) {
                            $arSegUsuarioProceso = $em->getRepository('App:Seguridad\SegUsuarioProceso')->find($codigoProceso);
                            if ($arSegUsuarioProceso) {
                                $em->remove($arSegUsuarioProceso);
                            }

                        }
                        $em->flush();
                        return $this->redirectToRoute('general_seguridad_usuario_modelo_lista', array('hash' => $hash));
                    }
                }
            }
        }
        $formSegmentos = $this->createFormBuilder()
            ->add('btnEliminarSegmento', SubmitType::class, ['label' => 'Eliminar'])
            ->getForm();
        $formSegmentos->handleRequest($request);
        if ($formSegmentos->isSubmitted() && $formSegmentos->isValid()) {
            if ($formSegmentos->get('btnEliminarSegmento')->isClicked()) {
                $arrSeleccionadosProceso = $request->request->get('ChkSeleccionarSegmentos');
                if ($arrSeleccionadosProceso && count($arrSeleccionadosProceso) > 0) {
                    if ($arrSeleccionadosProceso) {
                        foreach ($arrSeleccionadosProceso as $codigoProceso) {
                            $arSegUsuarioSegmento = $em->getRepository(SegUsuarioSegmento::class)->find($codigoProceso);
                            if ($arSegUsuarioSegmento) {
                                $em->remove($arSegUsuarioSegmento);
                            }

                        }
                        $em->flush();
                        return $this->redirectToRoute('general_seguridad_usuario_modelo_lista', array('hash' => $hash));
                    }
                }
            }
        }
        if ($id != "") {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('general_seguridad_usuario_modelo_lista'));
            }
            $arSeguridadUsuarioModelo = $em->getRepository(SegUsuarioModelo::class)->lista($arUsuario->getUsername());
            $arSeguridadUsuarioProceso = $em->getRepository(SegUsuarioProceso::class)->lista($arUsuario->getUsername());
            $arSeguridadUsuarioSegmento = $em->getRepository(SegUsuarioSegmento::class)->lista($arUsuario->getUsername());
//            $nombreUsuario=$arUsuario->getNombreCorto();
        }
        return $this->render('general/seguridad/seguridad_usuario_modelo/lista.html.twig', [
            'arSeguridadUsuarioModelo' => $arSeguridadUsuarioModelo,
            'arUsuario' => $arUsuario,
            'hash' => $hash,
            'form' => $form->createView(),
            'arSeguridadUsuarioProceso' => $arSeguridadUsuarioProceso,
            'formSegmento' => $formSegmentos->createView(),
            'arSeguridadUsuarioSegmento' => $arSeguridadUsuarioSegmento,
            'formProceso' => $formProcesos->createView(),
        ]);
    }

    /**
     * @Route("/general/seguridad/usuario/modelo/nuevo/{hash}", name="general_seguridad_usuario_modelo_nuevo")
     */
    public function nuevo(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('CboModelo', ChoiceType::class, array('placeholder' => 'TODOS', 'required' => false))
            ->add('cboModulo', EntityType::class, array(
                'class' => GenModulo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.codigoModuloPk', 'ASC');
                },
                'choice_label' => 'codigoModuloPk',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => $session->get('arSeguridadUsuarioModulofiltroModulo') || ""
            ))
            ->add('checkLista', CheckboxType::class, ['required' => false, 'label' => 'Lista'])
            ->add('checkDetalle', CheckboxType::class, ['required' => false, 'label' => 'Detalle'])
            ->add('checkNuevo', CheckboxType::class, ['required' => false, 'label' => 'Nuevo'])
            ->add('checkAutorizar', CheckboxType::class, ['required' => false, 'label' => 'Autorizar'])
            ->add('checkAprobar', CheckboxType::class, ['required' => false, 'label' => 'Aprobar'])
            ->add('checkAnular', CheckboxType::class, ['required' => false, 'label' => 'Anular'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);

        //eliminar variables de session solo cuando se cierre la pestaña

        if ($form->get('btnFiltrar')->isClicked()) {
            $arModeloSelect = $request->request->get('form');
            $arModeloSelect = $arModeloSelect['CboModelo'];
            $session->set('arSeguridadUsuarioModulofiltroModelo', $arModeloSelect);
            $session->set('arSeguridadUsuarioModulofiltroModulo', $form->get('cboModulo')->getData());
        }

        if ($form->get('btnGuardar')->isClicked()) {
            $id = $this->verificarUsuario($hash);
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arDatos = $form->getData();
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if ($arrSeleccionados) {
                foreach ($arrSeleccionados as $codigoModelo) {
                    $arGenModeloValidar = $em->getRepository('App:General\GenModelo')->find($codigoModelo);
                    if ($arGenModeloValidar && $arUsuario) {
                        $arSegUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->findOneBy(['codigoUsuarioFk' => $arUsuario->getUsername(), 'codigoModeloFk' => $arGenModeloValidar->getCodigoModeloPk()]);
                        if (!$arSegUsuarioModelo) {
                            $arSeguridadUsuarioModelo = new SegUsuarioModelo();
                            $arSeguridadUsuarioModelo->setCodigoModeloFk($arGenModeloValidar->getCodigoModeloPk());
                            $arSeguridadUsuarioModelo->setCodigoUsuarioFk($arUsuario->getUsername());
                            $arSeguridadUsuarioModelo->setLista($arDatos['checkLista']);
                            $arSeguridadUsuarioModelo->setDetalle($arDatos['checkDetalle']);
                            $arSeguridadUsuarioModelo->setNuevo($arDatos['checkNuevo']);
                            $arSeguridadUsuarioModelo->setAutorizar($arDatos['checkAutorizar']);
                            $arSeguridadUsuarioModelo->setAprobar($arDatos['checkAprobar']);
                            $arSeguridadUsuarioModelo->setAnular($arDatos['checkAnular']);
                            $em->persist($arSeguridadUsuarioModelo);
                        }
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                $session->set('arSeguridadUsuarioModulofiltroModelo', null);
                $session->set('arSeguridadUsuarioModulofiltroModulo', null);
            } else {
                Mensajes::error("No selecciono ningun dato para grabar");
            }
        }
        if (!$form->get('btnGuardar')->isClicked() && !$form->get('btnFiltrar')->isClicked()) {
            $session->set('arSeguridadUsuarioModulofiltroModelo', null);
            $session->set('arSeguridadUsuarioModulofiltroModulo', null);
        }
        $arGenModelo = $em->getRepository('App:General\GenModelo')->lista();
        return $this->render('general/seguridad/seguridad_usuario_modelo/nuevo.html.twig', [
            'form' => $form->createView(),
            'arGenModelo' => $arGenModelo,
            'arModeloSelect' => $arModeloSelect ?? ""
        ]);
    }

    /**
     * @Route("/general/seguridad/usuario/modelo/editar/{hash}/{codigoSeguridadUsuarioModelo}", name="general_seguridad_usuario_modelo_editar")
     */
    public function editarPermisos(Request $request, $hash, $codigoSeguridadUsuarioModelo)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        if ($id != "") {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('general_seguridad_usuario_modelo_lista'));
            }
        }
        if ($codigoSeguridadUsuarioModelo != 0) {
            $arSeguridadUsuarioModelo = $em->getRepository(SegUsuarioModelo::class)->find($codigoSeguridadUsuarioModelo);
            if ($arSeguridadUsuarioModelo) {
                $form = $this->createFormBuilder()
                    ->add('checkLista', CheckboxType::class, ['required' => false, 'label' => 'Lista', 'data' => $arSeguridadUsuarioModelo->getLista()])
                    ->add('checkDetalle', CheckboxType::class, ['required' => false, 'label' => 'Detalle', 'data' => $arSeguridadUsuarioModelo->getDetalle()])
                    ->add('checkNuevo', CheckboxType::class, ['required' => false, 'label' => 'Nuevo', 'data' => $arSeguridadUsuarioModelo->getNuevo()])
                    ->add('checkAutorizar', CheckboxType::class, ['required' => false, 'label' => 'Autorizar', 'data' => $arSeguridadUsuarioModelo->getAutorizar()])
                    ->add('checkAprobar', CheckboxType::class, ['required' => false, 'label' => 'Aprobar', 'data' => $arSeguridadUsuarioModelo->getAprobar()])
                    ->add('checkAnular', CheckboxType::class, ['required' => false, 'label' => 'Anular', 'data' => $arSeguridadUsuarioModelo->getAnular()])
                    ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    if ($form->get('btnGuardar')->isClicked()) {
                        $arSeguridadUsuarioModelo->setLista($form->get('checkLista')->getData());
                        $arSeguridadUsuarioModelo->setDetalle($form->get('checkDetalle')->getData());
                        $arSeguridadUsuarioModelo->setNuevo($form->get('checkNuevo')->getData());
                        $arSeguridadUsuarioModelo->setAutorizar($form->get('checkAutorizar')->getData());
                        $arSeguridadUsuarioModelo->setAprobar($form->get('checkAprobar')->getData());
                        $arSeguridadUsuarioModelo->setAnular($form->get('checkAnular')->getData());
                        $em->persist($arSeguridadUsuarioModelo);
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                }
            }
        }
        return $this->render('general/seguridad/seguridad_usuario_modelo/editar.html.twig', [
            'form' => $form->createView(),
//            'modulo' => $arSeguridadUsuarioModelo->getModeloRel()->getCodigoModuloFk(),
            'modelo' => $arSeguridadUsuarioModelo->getCodigoModeloFk(),
        ]);

    }


    private function verificarUsuario($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $id = 0;
        if ($hash != '0') {
            $arUsuarios = $em->getRepository('App:Seguridad\Usuario')->findAll();
            if (count($arUsuarios) > 0) {
                $hash = str_replace('&', '/', $hash);
                foreach ($arUsuarios as $arUsuario) {
                    if (password_verify($arUsuario->getUsername(), $hash)) {
                        $id = $arUsuario->getUsername();
                    }
                }
            }
        }
        return $id;
    }


}
