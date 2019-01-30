<?php

namespace App\Controller\General\Utilidad\General\Comentario;

use App\Controller\Estructura\FuncionesController;
use App\Entity\General\GenComentarioModelo;
use App\Form\Type\General\ComentarioModeloType;
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
     * @Route("general/utilidad/general/comentario/lista/{codigoModelo}/{codigo}", name="general_utilidad_general_comentario_lista")
     */
    public function lista(Request $request, $codigoModelo, $codigo)
    {
        $em = $this->getDoctrine()->getManager();
        $dia = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes'];
        $mes = ['Jan' => 'Enero', 'Feb' => 'Febrero', 'Mar' => 'Marzo', 'Apr' => 'Abril', 'May' => 'Mayo', 'Jun' => 'Junio', 'Jul' => 'Julio', 'Aug' => 'Agosto', 'Sep' => 'Septiembre', 'Oct' => 'Octubre', 'Nov' => 'Noviembre', 'Dec' => 'Diciembre'];
        $arComentarioModelo = new GenComentarioModelo();
        $form = $this->createForm(ComentarioModeloType::class, $arComentarioModelo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('comentar')->isClicked()) {
                $codigoComentario = $request->request->get('idComentario');
                if ($codigoComentario) {
                    $comentario = $arComentarioModelo->getComentario();
                    $arComentarioModelo = $em->find(GenComentarioModelo::class, $codigoComentario);
                    $arComentarioModelo->setComentario($comentario);
                } else {
                    $arComentarioModelo->setCodigoModeloFk($codigoModelo);
                    $arComentarioModelo->setCodigo($codigo);
                    $arComentarioModelo->setCodigoUsuario($this->getUser()->getUsername());
                    $usuarios = $em->getRepository(GenComentarioModelo::class)->usuariosComentario($codigoModelo, $codigo);
                    FuncionesController::crearNotificacion(4, "entidad " . $codigoModelo ." codigo: " . $codigo, $usuarios);
                }
                $arComentarioModelo->setFecha(new \DateTime('now'));
                $em->persist($arComentarioModelo);
                $em->flush();
                return $this->redirect($this->generateUrl('general_utilidad_general_comentario_lista', ['codigoModelo' => $codigoModelo, 'codigo' => $codigo]));
            }
        }
        $arComentariosModelo = $em->getRepository(GenComentarioModelo::class)->lista($codigoModelo, $codigo);
        return $this->render('general/utilidad/general/comentario/lista.html.twig', [
            'arComentariosModelo' => $arComentariosModelo,
            'dia' => $dia,
            'mes' => $mes,
            'form' => $form->createView()
        ]);
    }
}