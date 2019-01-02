<?php

namespace App\Controller\General\Utilidad\General\Comentario;

use App\Entity\General\GenMovimientoComentario;
use App\Form\Type\General\MovimientoComentarioType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MovimientoComentarioController extends Controller
{
    /**
     * @param Request $request
     * @param $codigoModelo
     * @param $codigoMovimiento
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("general/utilidad/general/comentario/lista/{codigoModelo}/{codigoMovimiento}", name="general_utilidad_general_comentario_lista")
     */
    public function lista(Request $request, $codigoModelo, $codigoMovimiento)
    {
        $em = $this->getDoctrine()->getManager();
        $dia = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes'];
        $mes = ['Jan' => 'Enero', 'Feb' => 'Febrero', 'Mar' => 'Marzo', 'Apr' => 'Abril', 'May' => 'Mayo', 'Jun' => 'Junio', 'Jul' => 'Julio', 'Aug' => 'Agosto', 'Sep' => 'Septiembre', 'Oct' => 'Octubre', 'Nov' => 'Noviembre', 'Dec' => 'Diciembre'];
        $arMovimientoComentario = new GenMovimientoComentario();
        $form = $this->createForm(MovimientoComentarioType::class, $arMovimientoComentario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('comentar')->isClicked()) {
                $codigoComentario = $request->request->get('idComentario');
                if ($codigoComentario) {
                    $comentario = $arMovimientoComentario->getComentario();
                    $arMovimientoComentario = $em->find(GenMovimientoComentario::class, $codigoComentario);
                    $arMovimientoComentario->setComentario($comentario);
                } else {
                    $arMovimientoComentario->setCodigoModeloFk($codigoModelo);
                    $arMovimientoComentario->setCodigoMovimientoFk($codigoMovimiento);
                    $arMovimientoComentario->setCodigoUsuario($this->getUser()->getUsername());
                }
                $arMovimientoComentario->setFecha(new \DateTime('now'));
                $em->persist($arMovimientoComentario);
                $em->flush();
                return $this->redirect($this->generateUrl('general_utilidad_general_comentario_lista', ['codigoModelo' => $codigoModelo, 'codigoMovimiento' => $codigoMovimiento]));
            }
        }
        $arMovimientoComentarios = $em->getRepository(GenMovimientoComentario::class)->lista($codigoModelo, $codigoMovimiento);
        return $this->render('general/utilidad/general/comentario/lista.html.twig', [
            'arMovimientoComentarios' => $arMovimientoComentarios,
            'dia' => $dia,
            'mes' => $mes,
            'form' => $form->createView()
        ]);
    }
}