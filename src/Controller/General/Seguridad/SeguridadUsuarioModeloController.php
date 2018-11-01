<?php

namespace App\Controller\General\Seguridad;

use App\Entity\Seguridad\SeguridadUsuarioModelo;
use App\Utilidades\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeguridadUsuarioModeloController extends AbstractController
{
    protected $clase = SeguridadUsuarioModelo::class;
    protected $claseNombre = "SeguridadUsuarioModelo";
    protected $modulo = "General";
    protected $funcion = "Seguridad";
    protected $grupo = "Seguridad";
    protected $nombre = "SeguridadUsuarioModelo";

    /**
     * @Route("/gen/seguridad/usuario/modelo/lista/{hash}", name="general_seguridad_usuario_modelo_lista")
     */
    public function lista($hash)
    {
        $em=$this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        $nombreUsuario="";
        if ($id != 0) {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('gen_seguridad_usuario_lista'));
            }
            $arSeguridadUsuarioModelo=$em->getRepository('App:Seguridad\SeguridadUsuarioModelo')->lista($arUsuario->getId());
            $nombreUsuario=$arUsuario->getNombreCorto();
        }
        return $this->render('general/seguridad/seguridad_usuario_modelo/lista.html.twig', [
            'arSeguridadUsuarioModelo'  =>  $arSeguridadUsuarioModelo,
            'arUsuarioNombre'           =>  $nombreUsuario,
            'hash'                      =>  $hash,
        ]);
    }

    /**
     * @Route("/gen/seguridad/usuario/modelo/nuevo/{hash}", name="general_seguridad_usuario_modelo_nuevo")
     */
    public function nuevo(Request $request, $hash){
        $em=$this->getDoctrine()->getManager();
        $session=new Session();
        $form = $this->createFormBuilder()
            ->add('TxtModulo', TextType::class, array('label'=>'Modelo','required' => false, 'data' => $session->get('arSeguridadUsuarioModulofiltroModulo')))
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
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')) {
                $session->set('arSeguridadUsuarioModulofiltroModulo',$form->get('TxtModulo')->getData());
            }

            if($form->get('btnGuardar')) {
                $id = $this->verificarUsuario($hash);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arDatos = $form->getData();
                if ($id != 0) {
                    $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
                }
                if ($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigoModelo) {
                        $arGenModeloValidar = $em->getRepository('App:General\GenModelo')->find($codigoModelo);
                        if ($arGenModeloValidar && $arUsuario) {
                            $arSeguridadUsuarioModelo = (new SeguridadUsuarioModelo())
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
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            }
            else{
                Mensajes::error("No selecciono ningun dato para grabar");
            }
        }
        $arGenModelo=$em->getRepository('App:General\GenModelo')->lista();
        return $this->render('general/seguridad/seguridad_usuario_modelo/nuevo.html.twig', [
            'form'          =>  $form->createView(),
            'arGenModelo'   =>  $arGenModelo,
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
                    if (password_verify($arUsuario->getId(), $hash)) {
                        $id = $arUsuario->getId();
                    }
                }
            }
        }
        return $id;
    }
}
