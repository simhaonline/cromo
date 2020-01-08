<?php

namespace App\Controller\General\Seguridad;

use App\Entity\General\GenModulo;
use App\Entity\General\GenProcesoTipo;
use App\Entity\General\GenSegmento;
use App\Entity\Seguridad\SegUsuarioProceso;
use App\Entity\Seguridad\SegUsuarioSegmento;
use App\Entity\Seguridad\Usuario;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SeguridadUsuarioSegmentoController extends AbstractController
{

    /**
     * @Route("/general/seguridad/usuario/segmento/nuevo/{hash}", name="general_seguridad_usuario_segmento_nuevo")
     */
    public function nuevo(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);

        //eliminar variables de session solo cuando se cierre la pestaña
        if ($form->get('btnGuardar')->isClicked()) {
            $id = $this->verificarUsuario($hash);
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arDatos = $form->getData();
            $arUsuario = $em->getRepository(Usuario::class)->find($id);
            if ($arrSeleccionados) {
                foreach ($arrSeleccionados as $codigoSegmento) {
                    $arSegmentoValidar = $em->getRepository(GenSegmento::class)->find($codigoSegmento);
                    if ($arSegmentoValidar && $arUsuario) {
                        $arSegUsuarioSegmento = $em->getRepository('App:Seguridad\SegUsuarioSegmento')->findOneBy(['codigoUsuarioFk' => $arUsuario->getUsername(), 'codigoSegmentoFk' => $arSegmentoValidar->getCodigoSegmentoPk()]);
                        if (!$arSegUsuarioSegmento) {
                            $arSegUsuarioSegmento = new SegUsuarioSegmento();
                            $arSegUsuarioSegmento->setSegmentoRel($arSegmentoValidar);
                            $arSegUsuarioSegmento->setUsuarioRel($arUsuario);
                            $em->persist($arSegUsuarioSegmento);
                        }
                    }
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                Mensajes::error("No selecciono ningun dato para grabar");
            }
        }
        $arSegmento = $em->getRepository(GenSegmento::class)->lista();
        return $this->render('general/seguridad/detalleNuevoSegmento.html.twig', [
            'form' => $form->createView(),
            'arSegmento' => $arSegmento
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
