<?php

namespace App\Controller\General\Seguridad;

use App\Entity\Seguridad\SeguridadUsuarioModelo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
        ]);
    }

    /**
     * @Route("/gen/seguridad/usuario/modelo/nuevo", name="general_seguridad_usuario_modelo_nuevo")
     */
    public function nuevo(Request $request){
        $em=$this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('selModelo', ChoiceType::class, ['required' => true])
            ->add('checkLista', CheckboxType::class, ['required' => false])
            ->add('checkDetalle', CheckboxType::class, ['required' => false])
            ->add('checkNuevo', CheckboxType::class, ['required' => false])
            ->add('checkAutorizar', CheckboxType::class, ['required' => false])
            ->add('checkAprobar', CheckboxType::class, ['required' => false])
            ->add('checkAnular', CheckboxType::class, ['required' => false])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        return $this->render('general/seguridad/seguridad_usuario_modelo/nuevo.html.twig', [
            'form'  =>  $form->createView(),
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
