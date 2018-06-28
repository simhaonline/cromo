<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion;


use App\Entity\RecursoHumano\RhuAspirante;
use App\Form\Type\RecursoHumano\AspiranteType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AspiranteController extends Controller
{
    /**
     * @Route("rhu/mov/seleccion/aspirante/nuevo/{id}", name="recursohumano_mov_seleccion_aspirante_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arAspirante = new RhuAspirante();
        if ($id != 0) {
            $arAspirante = $em->getRepository('App:RecursoHumano\RhuAspirante')->find($id);
            if (!$arAspirante) {
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano', 'entidad' => 'aspirante']));
            }
        }
        $form = $this->createForm(AspiranteType::class, $arAspirante);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arAspirante->setFecha(new \DateTime('now'));
                $em->persist($arAspirante);
                $em->flush();
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'aspirante']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arAspirante);
                $em->flush($arAspirante);
                return $this->redirect($this->generateUrl('recursohumano_mov_seleccion_aspirante_nuevo', ['codigoAspirante' => 0]));
            }
        }
        return $this->render('recursoHumano/movimiento/aspirante/nuevo.html.twig', [
            'form' => $form->createView(), 'arSolicitud' => $arAspirante
        ]);
    }
}

