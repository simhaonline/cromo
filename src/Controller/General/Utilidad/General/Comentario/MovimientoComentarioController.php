<?php

namespace App\Controller\General\Utilidad\General\Comentario;

use App\Entity\General\GenConfiguracion;
use App\Entity\General\GenMovimientoComentario;
use App\Form\Type\General\MovimientoComentarioType;
use App\Utilidades\Mensajes;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovimientoComentarioController extends Controller
{
    /**
     * @param Request $request
     * @param $codigoModelo
     * @param $codigoMovimiento
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("general/utilidad/general/comentario/lista/{codigoModelo}/{codigoMovimiento}", name="general_utilidad_general_comentario_lista")
     */
    public function lista(Request $request, $codigoModelo, $codigoMovimiento)
    {
        $em = $this->getDoctrine()->getManager();
        $dia = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miercoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes'];
        $mes = ['Jan' => 'Enero', 'Feb' => 'Febrero', 'Mar' => 'Marzo', 'Apr' => 'Abril', 'May' => 'Mayo', 'Jun' => 'Junio', 'Jul' => 'Julio', 'Aug' => 'Agosto', 'Sep' => 'Septiembre', 'Oct' => 'Octubre', 'Nov' => 'Noviembre', 'Dec' => 'Diciembre'];
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
        }
        $arMovimientoComentarios = $em->getRepository(GenMovimientoComentario::class)->lista($codigoModelo, $codigoMovimiento);
        return $this->render('general/utilidad/general/comentario/lista.html.twig', [
            'arMovimientoComentarios' => $arMovimientoComentarios,
            'dia' => $dia,
            'mes' => $mes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $codigoModelo
     * @param $codigoMovimiento
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("general/utilidad/general/comentario/nuevo/{codigoModelo}/{codigoMovimiento}/{id}", name="general_utilidad_general_comentario_nuevo")
     */
    public function nuevo(Request $request, $codigoModelo, $codigoMovimiento, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arMovimientoComentario = new GenMovimientoComentario();
        if ($id != 0) {
            $arMovimientoComentario = $em->find(GenMovimientoComentario::class, $id);
            if ($this->getUser()->getUsername() != $arMovimientoComentario->getCodigoUsuario()) {
                if(!$em->getRepository('App:Seguridad\SegUsuarioProceso')->findOneBy(['codigoUsuarioFk' => $this->getUser()->getUsername(), 'codigoProcesoFk' => '0005'])){
                    Mensajes::error('No esta autorizado para editar este comentario');
                    return $this->redirect($this->generateUrl('general_utilidad_general_comentario_lista', ['codigoModelo' => $codigoModelo, 'codigoMovimiento' => $codigoMovimiento]));
                }
            }
        } else {
            $arMovimientoComentario->setFecha(new \DateTime('now'));
            $arMovimientoComentario->setCodigoModeloFk($codigoModelo);
            $arMovimientoComentario->setCodigoMovimientoFk($codigoMovimiento);
        }
        $form = $this->createForm(MovimientoComentarioType::class, $arMovimientoComentario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')) {
                $arMovimientoComentario->setCodigoUsuario($this->getUser()->getUsername());
                $em->persist($arMovimientoComentario);
                $em->flush();
                return $this->redirect($this->generateUrl('general_utilidad_general_comentario_lista', ['codigoModelo' => $codigoModelo, 'codigoMovimiento' => $codigoMovimiento]));
            }
        }
        return $this->render('general/utilidad/general/comentario/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}