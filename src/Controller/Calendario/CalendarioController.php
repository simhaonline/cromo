<?php

namespace App\Controller\Calendario;

use App\Entity\Cartera\CarCliente;
use App\Entity\General\GenEvento;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

class CalendarioController extends Controller
{
    /**
     * @Route("/calendario/lista", name="calendario_lista")
     */
    public function lista(Request $request)
    {
        return $this->render('calendario/calendario.html.twig');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/calendario/evento/guardar", name="calendario_evento_guardar")
     */
    public function guardarEvento(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arrEvento['icono'] = $request->query->get('icono');
        $arrEvento['titulo'] = $request->query->get('titulo');
        $arrEvento['desde'] = $request->query->get('desde');
        $arrEvento['hasta'] = $request->query->get('hasta');
        $arrEvento['descripcion'] = $request->query->get('descripcion');
        $arrEvento['color'] = $request->query->get('color');

        $arEvento = new GenEvento();
        $arEvento->setIcono($arrEvento['icono']);
        $arEvento->setTitulo($arrEvento['titulo']);
        $arEvento->setFechaDesde(date_create($arrEvento['desde']));
        $arEvento->setFechaHasta(date_create($arrEvento['hasta']));
        $arEvento->setDescripcion($arrEvento['descripcion']);
        $arEvento->setColor($arrEvento['color']);
        $arEvento->setUsuario($this->getUser()->getUsername());
        $em->persist($arEvento);

        $respuesta = true;
        try {
            $em->flush();
        } catch (\Exception $exception) {
        }
        return new JsonResponse($respuesta);
    }

    /**
     * @return JsonResponse
     * @Route("/calendario/evento/listar", name="calendario_evento_listar")
     */
    public function cargarEventos()
    {
        $em = $this->getDoctrine()->getManager();
        $arEventos = $em->getRepository(GenEvento::class)->jsonEventos($this->getUser()->getUsername());
        return new JsonResponse($arEventos);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @Route("/calendario/evento/eliminar", name="calendario_evento_eliminar")
     */
    public function eliminarEventos(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $em->getRepository(GenEvento::class)->eliminar($id);
        return new JsonResponse(true);
    }
}

