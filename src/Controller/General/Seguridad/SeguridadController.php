<?php

namespace App\Controller\General\Seguridad;

use App\Controller\Estructura\MensajesController;
use App\Entity\Seguridad\Usuario;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SeguridadController extends Controller
{
    /**
     * @Route("/gen/seguridad/usuario/lista", name="gen_seguridad_usuario_lista")
     */
    public function lista(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arUsuarios = $em->getRepository('App:Seguridad\Usuario')->findAll();
        return $this->render('general/seguridad/lista.html.twig', [
            'arUsuarios' => $arUsuarios
        ]);
    }

    /**
     * @Route("/gen/seguridad/usuario/nuevo/{hash}", name="gen_seguridad_usuario_nuevo")
     */
    public function nuevo(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $arUsuario = new Usuario();
        $id = $this->verificarUsuario($hash);
        if ($id != 0) {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('gen_seguridad_usuario_lista'));
            }
        }
        $form = $this->createFormBuilder()
            ->add('txtUser', TextType::class, ['data' => $arUsuario->getUsername()])
            ->add('txtEmail', TextType::class, ['data' => $arUsuario->getEmail()])
            ->add('boolActivo', CheckboxType::class, ['data' => $arUsuario->getisActive(), 'label' => ' '])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-smbtn-primary']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnGuardar')->isClicked()) {
                $arUsuario->setUsername($form->get('txtUser')->getData());
                $arUsuario->setEmail($form->get('txtEmail')->getData());
                $arUsuario->setIsActive($form->get('boolActivo')->getData());
                $em->persist($arUsuario);
            }
            $em->flush();
        }
        return $this->render('general/seguridad/nuevo.html.twig', [
            'arUsuario' => $arUsuario,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/gen/seguridad/usuario/nuevo/clave/{hash}", name="gen_seguridad_usuario_nuevo_clave")
     */
    public function cambiarClave(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $this->verificarUsuario($hash);
        $respuesta = '';
        if ($id != 0) {
            $arUsuario = $em->getRepository('App:Seguridad\Usuario')->find($id);
            if (!$arUsuario) {
                return $this->redirect($this->generateUrl('gen_seguridad_usuario_lista'));
            }
        }
        $form = $this->createFormBuilder()
            ->add('txtClaveActual', PasswordType::class, ['required' => true])
            ->add('txtNuevaClave', PasswordType::class, ['required' => true])
            ->add('txtConfirmacionClave', PasswordType::class, ['required' => true])
            ->add('btnActualizar', SubmitType::class, ['label' => 'Actualizar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnActualizar')->isClicked()) {
                $claveActual = $form->get('txtClaveActual')->getData();
                $claveNueva = $form->get('txtNuevaClave')->getData();
                $claveConfirmacion = $form->get('txtConfirmacionClave')->getData();
                if (password_verify($claveActual, $arUsuario->getPassword())) {
                    if ($claveNueva == $claveConfirmacion) {
                        $arUsuario->setPassword(password_hash($claveNueva, PASSWORD_BCRYPT));
                        $em->persist($arUsuario);
                    } else {
                        $respuesta = "Las claves ingresadas no coindicen";
                    }
                } else {
                    $respuesta = "La contraseña ingresada es incorrecta";
                }
            }
            if ($respuesta != '') {
                MensajesController::error($respuesta);
            } else {
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('general/seguridad/cambioClave.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function verificarUsuario($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $id = 0;
        $arUsuarios = $em->getRepository('App:Seguridad\Usuario')->findBy(['isActive' => true]);
        if (count($arUsuarios) > 0) {
            $hash = str_replace('&', '/', $hash);
            foreach ($arUsuarios as $arUsuario) {
                if (password_verify($arUsuario->getId(), $hash)) {
                    $id = $arUsuario->getId();
                }
            }
        }
        return $id;
    }
}

