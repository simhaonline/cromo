<?php

namespace App\Controller\General\Seguridad;

use App\Entity\General\GenModulo;
use App\Entity\Seguridad\SegUsuarioModelo;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SeguridadUsuarioModeloController extends AbstractController
{
    protected $clase = SegUsuarioModelo::class;
    protected $claseNombre = "SegUsuarioModelo";
    protected $modulo = "General";
    protected $funcion = "Seguridad";
    protected $grupo = "Seguridad";
    protected $nombre = "SegUsuarioModelo";

    /**
     * @Route("/gen/seguridad/usuario/modelo/lista/{hash}", name="general_seguridad_usuario_modelo_lista")
     */
    public function lista($hash, Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        $nombreUsuario="";
        $form = $this->createFormBuilder()
            ->add('btnEliminar',SubmitType::class,['label'=>'Eliminar'])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($form->get('btnEliminar')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermiso');
                if($arrSeleccionados && count($arrSeleccionados)>0){
                    if ($arrSeleccionados) {
                        foreach ($arrSeleccionados as $codigoSeguridad) {
                            $arSegUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->find($codigoSeguridad);
                            if($arSegUsuarioModelo){
                                $em->remove($arSegUsuarioModelo);
                            }

                        }
                        $em->flush();
                        return $this->redirectToRoute('general_seguridad_usuario_modelo_lista',array('hash'=>$hash));
                    }
                }
            }
        }
        if ($id != "") {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('gen_seguridad_usuario_lista'));
            }
            $arSeguridadUsuarioModelo=$em->getRepository('App:Seguridad\SegUsuarioModelo')->lista($arUsuario->getUsername());
            $nombreUsuario=$arUsuario->getNombreCorto();
        }
        return $this->render('general/seguridad/seguridad_usuario_modelo/lista.html.twig', [
            'arSeguridadUsuarioModelo'  =>  $arSeguridadUsuarioModelo,
            'arUsuarioNombre'           =>  $nombreUsuario,
            'hash'                      =>  $hash,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/gen/seguridad/usuario/modelo/nuevo/{hash}", name="general_seguridad_usuario_modelo_nuevo")
     */
    public function nuevo(Request $request, $hash){
        $em=$this->getDoctrine()->getManager();
        $session=new Session();
        $form = $this->createFormBuilder()
            ->add('TxtModelo', TextType::class, array('label'=>'Modelo','required' => false, 'data' => $session->get('arSeguridadUsuarioModulofiltroModelo')))
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
                'data' => $session->get('arSeguridadUsuarioModulofiltroModulo')||""
            ))
            ->add('checkLista', CheckboxType::class, ['required' => false, 'label'=>'Lista'])
            ->add('checkDetalle', CheckboxType::class, ['required' => false, 'label'=>'Detalle'])
            ->add('checkNuevo', CheckboxType::class, ['required' => false, 'label'=>'Nuevo'])
            ->add('checkAutorizar', CheckboxType::class, ['required' => false, 'label'=>'Autorizar'])
            ->add('checkAprobar', CheckboxType::class, ['required' => false, 'label'=>'Aprobar'])
            ->add('checkAnular', CheckboxType::class, ['required' => false, 'label'=>'Anular'])
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('arSeguridadUsuarioModulofiltroModelo',$form->get('TxtModelo')->getData());
                $session->set('arSeguridadUsuarioModulofiltroModulo',$form->get('cboModulo')->getData());
            }
            if($form->get('btnGuardar')->isClicked()) {
                $id = $this->verificarUsuario($hash);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arDatos = $form->getData();
                if ($id != "") {
                    $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
                }
                if ($arrSeleccionados) {
                    $error=false;
                    foreach ($arrSeleccionados as $codigoModelo) {
                        $arGenModeloValidar = $em->getRepository('App:General\GenModelo')->find($codigoModelo);
                        if ($arGenModeloValidar && $arUsuario) {
                            $arSegUsuarioModelo=$em->getRepository('App:Seguridad\SegUsuarioModelo')->findOneBy(['codigoUsuarioFk'=>$arUsuario->getUsername(),'codigoGenModeloFk'=>$arGenModeloValidar->getCodigoModeloPk()]);
                            if(!$arSegUsuarioModelo) {
                                $arSeguridadUsuarioModelo = (new SegUsuarioModelo())
                                    ->setGenModeloRel($arGenModeloValidar)
                                    ->setUsuarioRel($arUsuario)
                                    ->setLista($arDatos['checkLista'])
                                    ->setDetalle($arDatos['checkDetalle'])
                                    ->setNuevo($arDatos['checkNuevo'])
                                    ->setAutorizar($arDatos['checkAutorizar'])
                                    ->setAprobar($arDatos['checkAprobar'])
                                    ->setAnular($arDatos['checkAnular']);

                                $em->persist($arSeguridadUsuarioModelo);
                            }
                            else{
                                $error=true;
                                Mensajes::error("El modelo '{$arGenModeloValidar->getCodigoModeloPk()}' ya tiene permisos asignados, si desea modificar, puede hacerlo desde el detalle");
                            }
                        }
                    }
                    if(!$error){
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    $session->set('arSeguridadUsuarioModulofiltroModelo',null);
                    $session->set('arSeguridadUsuarioModulofiltroModulo',null);
                    }
                }
                else{
                    Mensajes::error("No selecciono ningun dato para grabar");
                }
            }
        }
        $arGenModelo=$em->getRepository('App:General\GenModelo')->lista();
        return $this->render('general/seguridad/seguridad_usuario_modelo/nuevo.html.twig', [
            'form'          =>  $form->createView(),
            'arGenModelo'   =>  $arGenModelo,
        ]);
    }

    /**
     * @Route("/gen/seguridad/usuario/modelo/editar/{hash}/{codigoSeguridadUsuarioModelo}", name="general_seguridad_usuario_modelo_editar")
     */
    public function editarPermisos(Request $request, $hash, $codigoSeguridadUsuarioModelo){
        $em=$this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        if ($id != "") {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('gen_seguridad_usuario_lista'));
            }
        }
        if ($codigoSeguridadUsuarioModelo != 0) {
            $arSeguridadUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->find($codigoSeguridadUsuarioModelo);
        if($arSeguridadUsuarioModelo) {
            $form = $this->createFormBuilder()
                ->add('checkLista', CheckboxType::class, ['required' => false, 'label'=>'Lista','data'=>$arSeguridadUsuarioModelo->getLista()])
                ->add('checkDetalle', CheckboxType::class, ['required' => false, 'label'=>'Detalle','data'=>$arSeguridadUsuarioModelo->getDetalle()])
                ->add('checkNuevo', CheckboxType::class, ['required' => false, 'label'=>'Nuevo','data'=>$arSeguridadUsuarioModelo->getNuevo()])
                ->add('checkAutorizar', CheckboxType::class, ['required' => false, 'label'=>'Autorizar','data'=>$arSeguridadUsuarioModelo->getAutorizar()])
                ->add('checkAprobar', CheckboxType::class, ['required' => false, 'label'=>'Aprobar','data'=>$arSeguridadUsuarioModelo->getAprobar()])
                ->add('checkAnular', CheckboxType::class, ['required' => false, 'label'=>'Anular','data'=>$arSeguridadUsuarioModelo->getAnular()])
                ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('btnGuardar')->isClicked()) {

                    $arSeguridadUsuarioModelo
                        ->setLista($form->get('checkLista')->getData())
                        ->setDetalle($form->get('checkDetalle')->getData())
                        ->setNuevo($form->get('checkNuevo')->getData())
                        ->setAutorizar($form->get('checkAutorizar')->getData())
                        ->setAprobar($form->get('checkAprobar')->getData())
                        ->setAnular($form->get('checkAnular')->getData())
                        ->setUsuarioRel($arUsuario);
                    $em->persist($arSeguridadUsuarioModelo);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
        }
        }
        return $this->render('general/seguridad/seguridad_usuario_modelo/editar.html.twig',[
           'form'=>$form->createView(),
            'modulo'=>$arSeguridadUsuarioModelo->getGenModeloRel()->getCodigoModuloFk(),
            'modelo'=>$arSeguridadUsuarioModelo->getCodigoGenModeloFk(),
        ]);

    }

//    /**
//     * @Route("/gen/seguridad/usuario/modelo/eliminar", name="general_seguridad_usuario_modelo_eliminar")
//     */
//    public function eliminarPermiso(Request $request){
//        $em=$this->getDoctrine()->getManager();
//        $arrSeleccionados = $request->request->get('ChkSeleccionar');
//        if($arrSeleccionados && count($arrSeleccionados)>0){
//            if ($arrSeleccionados) {
//                foreach ($arrSeleccionados as $codigoSeguridad) {
//                    $arSegUsuarioModelo = $em->getRepository('App:Seguridad\SegUsuarioModelo')->find($codigoSeguridad);
//                    if($arSegUsuarioModelo){
//                        $em->remove($arSegUsuarioModelo);
//                    }
//
//                }
//                $em->flush();
//            }
//        }
//
//        return $this->redirectToRoute('general_seguridad_usuario_modelo_lista');
//    }

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
