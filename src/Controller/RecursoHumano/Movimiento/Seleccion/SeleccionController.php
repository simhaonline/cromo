<?php

namespace App\Controller\RecursoHumano\Movimiento\Seleccion;


use App\Entity\RecursoHumano\RhuSeleccion;
use App\Form\Type\RecursoHumano\SeleccionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SeleccionController extends Controller
{
    /**
     * @Route("rhu/mov/seleccion/seleccion/nuevo/{id}", name="rhu_mov_seleccion_seleccion_nuevo")
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
                return $this->redirect($this->generateUrl('admin_lista', ['modulo' => 'recursoHumano','entidad' => 'seleccion']));
            }
            if ($form->get('guardarnuevo')->isClicked()) {
                $em->persist($arSeleccion);
                $em->flush($arSeleccion);
                return $this->redirect($this->generateUrl('rhu_mov_seleccion_seleccion_nuevo', ['codigoSeleccion' => 0]));
            }
        }
        return $this->render('recursoHumano/movimiento/seleccion/nuevo.html.twig', [
            'form' => $form->createView(), 'arSeleccion' => $arSeleccion
        ]);
    }
}

